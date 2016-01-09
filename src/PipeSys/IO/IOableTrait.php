<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\IOException;
use ElvenSpellmaker\PipeSys\IO\IOInterface;
use ElvenSpellmaker\PipeSys\IO\ReadIntent;

/**
 * Prodives IOable helper methods.
 */
trait IOableTrait
{
	/**
	 * @var ReadIntent|null
	 */
	private $readIntent;

	/**
	 * @var array
	 */
	protected $ios = [];

	/**
	 * Returns true if the IOable is reading.
	 *
	 * @return boolean
	 */
	public function isReading()
	{
		return $this->readIntent !== null;
	}

	/**
	 * Sets an IO channel recording if it's an InputInterface and/or an
	 * OutputInterface.
	 *
	 * @param IOInterface $ioObject
	 * @param integer     $key
	 *
	 * @return $this
	 */
	public function setIOChannel(IOInterface $ioObject, $key)
	{
		$this->ios[$key] = [
			'channel' => $ioObject,
			'input' => $ioObject instanceof InputInterface,
			'output' => $ioObject instanceof OutputInterface,
		];

		return $this;
	}

	/**
	 * Gets a channel with the associated key.
	 *
	 * @param integer $key
	 *
	 * @return IOInterface|null
	 */
	public function getIOChannel($key)
	{
		return isset($this->ios[$key])
			? $this->ios[$key]['channel']
			: null;
	}

	/**
	 * Removes a specific IO Channel from the IO list. Returns true if this
	 * resource exists.
	 *
	 * @param integer $key
	 *
	 * @return boolean
	 */
	public function removeIOChannel($key)
	{
		$exists = isset($this->ios[$key]);

		unset($this->ios[$key]);

		return $exists;
	}

	/**
	 * Attempts to read from the input channel in the stored `ReadIntent`.
	 *
	 * @return mixed
	 */
	protected function attemptRead()
	{
		if ($this->isReading())
		{
			$data = $this->read($this->readIntent->getChannel());

			if ($data)
			{
				$this->readIntent = null;
			}

			return $data;
		}
	}

	/**
	 * Reads from a specific channel if it exists.
	 *
	 * @param integer $key
	 *
	 * @return mixed
	 *
	 * @throws IOException
	 */
	public function read($key)
	{
		if (! isset($this->ios[$key]))
		{
			throw new IOException("The position '$key' is not set!");
		}

		if (! $this->ios[$key]['input'])
		{
			throw new IOException("The IOableObject in position '$key' does not implement the InputInterface!");
		}

		return $this->ios[$key]['channel']->read();
	}

	/**
	 * Writes data to the specified key if it exists.
	 *
	 * @param integer $key
	 * @param mixed   $data
	 *
	 * @return mixed
	 *
	 * @throws IOException
	 */
	public function write($key, $data)
	{
		if (! isset($this->ios[$key]))
		{
			throw new IOException("The position '$key' is not set!");
		}

		if (! $this->ios[$key]['output'])
		{
			throw new IOException("The IOableObject in position '$key' does not implement the OutputInterface!");
		}

		return $this->ios[$key]['channel']->write($data);
	}
}
