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
	public function run()
	{
		$this->connector->connect($this->commands);

		while (count($this->commands))
		{
			foreach ($this->commands as $key => $command)
			{
				$result = $command->runOnce();

				if ($result === null)
				{
					unset($this->commands[$key]);
				}
			}
		}
	}
}
