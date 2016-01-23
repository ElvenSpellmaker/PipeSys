<?php

namespace ElvenSpellmaker\PipeSys;

use ElvenSpellmaker\PipeSys\Command\AbstractCommand;
use ElvenSpellmaker\PipeSys\Command\ConnectorInterface;

/**
 * The Scheduler is the main logic for pipes, commands are added to the
 * scheduler which are processed when `run()` is called.
 */
class Scheduler
{
	/**
	 * @var AbstractCommand[]
	 */
	private $commands = [];

	/**
	 * @var ConnectorInterface
	 */
	private $connector;

	/**
	 * @var boolean
	 */
	private $started;

	/**
	 * @param ConnectorInterface $connector The connector to connect commands
	 * together.
	 */
	public function __construct(ConnectorInterface $connector)
	{
		$this->connector = $connector;
	}

	/**
	 * Adds a new command to the Scheduler for piping.
	 *
	 * @param AbstractCommand $command
	 *
	 * @return $this
	 */
	public function addCommand(AbstractCommand $command)
	{
		$this->commands[] = $command;

		return $this;
	}

	/**
	 * Runs all the commands added to the scheduler.
	 */
	public function run($runs = true)
	{
		if ($runs !== true && ! is_integer($runs))
		{
			$runs = (int)$runs;
		}

		if (! $this->started)
		{
			$this->started = true;
			$this->connector->connect($this->commands);
		}

		while (count($this->commands))
		{
			if ($runs-- <= 0)
			{
				break;
			}

			foreach ($this->commands as $key => $command)
			{
				$result = $command->runOnce();

				if ($result === null || ! $command->isValid())
				{
					unset($this->commands[$key]);
				}
			}
		}

		return $this->commands;
	}

	/**
	 * Has the scheduler already been run before?
	 *
	 * @return boolean
	 */
	public function isStarted()
	{
		return $this->started;
	}
}
