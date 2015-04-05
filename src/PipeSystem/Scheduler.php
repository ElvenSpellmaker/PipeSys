<?php

namespace ElvenSpellmaker\PipeSystem;

use ElvenSpellmaker\PipeSystem\Command\CommandInterface;
use ElvenSpellmaker\PipeSystem\IO\ReadIntent;
use Generator;

/**
 * The Scheduler is the main logic for pipes, commands are added to the
 * scheduler which are processed when `run()` is called.
 */
class Scheduler
{
	/**
	 * @var array
	 */
	protected $commands = [];

	/**
	 * @var Generator[]
	 */
	protected $commandGenerators = [];

	/**
	 * @var string[]
	 */
	protected $outputs = [];

	/**
	 * @var bool[]
	 */
	protected $readIntents = [];

	protected $stdOut;

	public function __construct($stdOut)
	{
		$this->stdOut = $stdOut;
	}

	public function addCommand(CommandInterface $command)
	{
		$this->commands[] = $command;
	}

	public function run()
	{
		$this->prepareCommands();

		while( true )
		{
			foreach( $this->commandGenerators as $key => $gen )
			{
				if ( ! $gen->valid() ) break 2;

				if ( isset( $this->readIntents[$key] ) )
				{
					$previousGenKey = $key - 1;
					if ( isset( $this->outputs[$previousGenKey] ) )
					{
						$output = $this->outputs[$previousGenKey];
						unset( $this->outputs[$previousGenKey], $this->readIntents[$key] );
					}
					else continue;
				}

				if( isset( $output ) )
				{
					$genResponse = $gen->send( $output );
					$gen->next();

					unset( $output );
				}
				elseif( ! isset( $this->outputs[$key] ) )
				{
					$genResponse = $gen->current();
					if( ! $genResponse instanceof ReadIntent ) $gen->next();
				}
				else continue;

				if( $genResponse instanceof ReadIntent )
					$this->readIntents[$key] = true;
				else
				{
					unset( $this->readIntents[$key] );

					if( $this->commandGenerators[$key] === end( $this->commandGenerators ) )
						$this->stdOut->write( $genResponse );
					else
						$this->outputs[$key] = $genResponse;
				}

				unset( $genResponse );
			}
		}
	}

	public function prepareCommands()
	{
		foreach( $this->commands as $key => $commands )
			$this->commandGenerators[$key] = $commands->doCommand();
	}
}