<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\CommandInterface;
use ElvenSpellmaker\PipeSys\IO\BufferInterface;
use ElvenSpellmaker\PipeSys\IO\InvalidBufferException;
use ElvenSpellmaker\PipeSys\IO\IOableTrait;
use ElvenSpellmaker\PipeSys\IO\OutputIntent;
use ElvenSpellmaker\PipeSys\IO\ReadIntent;
use ElvenSpellmaker\PipeSys\IO\Std\StdIoTrait;
use Generator;
use RuntimeException;

/**
 * Commands implement a generator method which can be called. Each Command can
 * have input and output channels.
 */
abstract class AbstractCommand implements CommandInterface
{
	use IOableTrait;
	use StdIoTrait;

	/**
	 * @var Generator
	 */
	protected $generator;

	/**
	 * @var boolean
	 */
	private $isSetup = false;

	/**
	 * @var boolean
	 */
	private $isValid = true;

	/**
	 * {@inheritdoc}
	 */
	public function runOnce()
	{
		if (! $this->isValid)
		{
			return null;
		}

		$firstRun = false;

		if (! $this->isSetup)
		{
			$this->prepareCommand();
			$firstRun = true;
		}

		return $this->attemptRun($firstRun);
	}

	/**
	 * Attempts to run the command once.
	 *
	 * @param boolean $firstRun
	 *
	 * @return boolean
	 *
	 * @throws InvalidBufferException
	 */
	private function attemptRun($firstRun)
	{
		$data = null;

		if ($this->isReading())
		{
			$data = $this->attemptRead();

			if ($data instanceof EOF)
			{
				$this->invalidate();
			}

			if ($this->isReading())
			{
				return false;
			}
		}
		elseif (! $firstRun)
		{
			$this->generator->next();
		}

		$genResponse = $data
			? $this->generator->send($data)
			: $this->generator->current();

		return $this->handleResponse($genResponse);
	}

	/**
	 * Attempts to prepare the command by setting up the generator.
	 *
	 * @throws RuntimeException
	 */
	private function prepareCommand()
	{
		if (! ($generator = $this->getCommand()) instanceof Generator)
		{
			throw new RuntimeException('Not a valid generator!');
		}

		$this->generator = $generator;

		$this->isSetup = true;
	}

	/**
	 * Returns if the current command is valid or not.
	 *
	 * @return boolean
	 */
	final public function isValid()
	{
		return $this->isValid;
	}

	/**
	 * Invalidates the command.
	 *
	 * @return void
	 */
	final public function invalidate()
	{
		$this->isValid = false;

		foreach ($this->ios as $io)
		{
			$io = $io['channel'];
			$io instanceof BufferInterface && $io->invalidate();
		}
	}

	/**
	 * Handles the generator response.
	 *
	 * @param OutputIntent|ReadIntent|null $data
	 *
	 * @return boolean
	 *
	 * @throws InvalidBufferException
	 */
	private function handleResponse($data)
	{
		if ($data instanceof OutputIntent)
		{
			try
			{
				$this->write($data->getChannel(), $data->getContent());
			}
			catch (InvalidBufferException $e)
			{
				$this->invalidate();
			}
		}
		if ($data instanceof ReadIntent)
		{
			$this->readIntent = $data;
		}
		elseif ($data === null)
		{
			$this->invalidate();
			return null;
		}

		return true;
	}
}
