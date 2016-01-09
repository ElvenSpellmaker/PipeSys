<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\ConnectorInterface;
use ElvenSpellmaker\PipeSys\IO\BufferFactoryInterface;
use ElvenSpellmaker\PipeSys\IO\InputInterface;
use ElvenSpellmaker\PipeSys\IO\OutputInterface;
use ElvenSpellmaker\PipeSys\IO\QueueBufferFactory;
use ElvenSpellmaker\PipeSys\IO\Std\StdErr;
use ElvenSpellmaker\PipeSys\IO\Std\StdIn;
use ElvenSpellmaker\PipeSys\IO\Std\StdOut;

/**
 * This connector connects the standard channels to commands, it does not
 * override exisiting channels and hooks all commands up to StdErr too.
 */
class StandardConnector implements ConnectorInterface
{
	/**
	 * @var InputInterface
	 */
	private $input;

	/**
	 * @var OutputInterface
	 */
	private $output;

	/**
	 * @var BufferFactoryInterface
	 */
	private $bufferFactory;

	/**
	 * @var OutputInterface
	 */
	private $error;

	/**
	 * {@inheritdoc}
	 */
	public function connect(array $commands)
	{
		$this->prepareConnectors();

		$commandCount = count($commands);

		if (! $commandCount)
		{
			return;
		}

		$this->connectTerminalChannels($commands);

		if ($commandCount === 1)
		{
			return;
		}

		$this->connectCommands($commands, $commandCount);
	}

	/**
	 * Connects up the terminal channels to the first and last command.
	 *
	 * @param array $commands
	 * #@param AbstractCommand[] $commands <-- Bug in the sniffer
	 */
	private function connectTerminalChannels(array $commands)
	{
		$firstCommand = $commands[0];
		if ($firstCommand->getStdIn() ===  null)
		{
			$firstCommand->setStdIn($this->input);
		}

		$lastCommand = end($commands);
		if ($lastCommand->getStdOut() === null)
		{
			$lastCommand->setStdOut($this->output);
		}
	}

	/**
	 * Connects all the other commands together and gives them a StdErr too.
	 *
	 * @param array   $commands
	 * @param integer $commandCount
	 * #@param AbstractCommand[] $commands <-- Bug in the sniffer
	 */
	private function connectCommands(array $commands, $commandCount)
	{
		for ($i = 1; $i < $commandCount; $i++)
		{
			$outCommand = $commands[$i - 1];
			$inCommand = $commands[$i];
			if ($outCommand->getStdOut() === null
				&& $inCommand->getStdIn() === null
			)
			{
				$buffer = $this->bufferFactory->create();

				$outCommand->setStdOut($buffer);
				$inCommand->setStdIn($buffer);

				$outCommand->setStdErr($this->error);
				$inCommand->setStdErr($this->error);
			}
		}
	}

	/**
	 * Prepares the buffers and input/output channels.
	 */
	private function prepareConnectors()
	{
		if ($this->input === null)
		{
			$this->input = new StdIn;
		}

		if ($this->output === null)
		{
			$this->output = new StdOut;
		}

		if ($this->bufferFactory === null)
		{
			$this->bufferFactory = new QueueBufferFactory;
		}

		if ($this->error === null)
		{
			$this->error = new StdErr;
		}
	}
}
