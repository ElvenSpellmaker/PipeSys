<?php

namespace ElvenSpellmaker\PipeSys;

use ElvenSpellmaker\PipeSys\Command\CommandInterface;
use ElvenSpellmaker\PipeSys\IO\BufferInterface;
use ElvenSpellmaker\PipeSys\IO\InputInterface;
use ElvenSpellmaker\PipeSys\IO\OutputInterface;
use ElvenSpellmaker\PipeSys\IO\QueueBuffer;
use ElvenSpellmaker\PipeSys\IO\ReadIntent;
use Generator;

/**
 * The Scheduler is the main logic for pipes, commands are added to the
 * scheduler which are processed when `run()` is called.
 */
class Scheduler
{
	const STDIN = -1;

	/**
	 * @var array
	 */
	protected $commands = [];

	/**
	 * @var Generator[]
	 */
	protected $commandGenerators = [];

	/**
	 * @var BufferInterface
	 */
	protected $buffers = [];

	/**
	 * @var bool[]
	 */
	protected $readIntents = [];

	/**
	 * @var InputInterface
	 */
	protected $stdIn;

	/**
	 * @var OutputInterface
	 */
	protected $stdOut;

	/**
	 * @param InputInterface $stdIn The standard Input to be used for the first
	 * command to read from.
	 * @param OutputInterface $stdOut The Standard Out to be used for the last
	 * command to write to.
	 */
	public function __construct(InputInterface $stdIn, OutputInterface $stdOut)
	{
		$this->stdIn = $stdIn;
		$this->stdOut = $stdOut;
	}

	/**
	 * Adds a new command to the Scheduler for piping. Its input will be
	 * connected to the previously added command (or stdIn for the first
	 * command) and its output will be connected to the next command's input
	 * unless this is the last command to be added in which case the stdOut will
	 * be used.
	 *
	 * @param CommandInterface $command
	 *
	 * @return $this
	 */
	public function addCommand(CommandInterface $command)
	{
		$this->commands[] = $command;

		return $this;
	}

	/**
	 * Runs all the commands added to the scheduler.
	 */
	public function run()
	{
		$this->prepareCommands();

		while( true )
		{
			foreach( $this->commandGenerators as $key => $gen )
			{
				/** @todo This needs to be replaced as it's not strictly correct! */
				if ( ! $gen->valid() ) break 2;

				// Check to see if this command is intending on reading and
				// if so gets the data if available.
				if( ( $readLine = $this->checkForLine($key) ) === false ) continue;

				if( ( $genResponse = $this->runGen( $key, $readLine ) ) === false )
					continue;

				$this->handleGenResponse($genResponse, $key);
			}
		}
	}

	/**
	 * Prepapres all commands by getting their generators from `doCommand()`
	 * and handles the first response from each generator.
	 */
	protected function prepareCommands()
	{
		foreach( $this->commands as $key => $commands )
		{
			$this->commandGenerators[$key] = $commands->doCommand();

			/** @todo This should be injectable... But how‽ */
			$this->buffers[$key] = new QueueBuffer;

			// Run the generators once and handle the responses.
			$genResponse = $this->commandGenerators[$key]->current();
			$this->handleGenResponse($genResponse, $key);
		}
	}

	/**
	 * For a given key, attempts to see if that generator is looking to read,
	 * and if the data is available return the data, unset it from the
	 * previous command and unset the current read intent for this current
	 * command.
	 *
	 * @param integer $key
	 *
	 * @return string|boolean|null Returns the data on success, false if there
	 * is no data to read (but we intend to get data), and null if we aren't
	 * looking for any data.
	 */
	protected function checkForLine($key)
	{
		$output = null;

		if ( isset( $this->readIntents[$key] ) )
		{
			$previousGenKey = $key - 1;
			if( $previousGenKey === static::STDIN )
				$output = $this->stdIn->read();
			else $output = $this->buffers[$previousGenKey]->read();

			if( $output !== false ) unset( $this->readIntents[$key] );
		}

		return $output;
	}

	/**
	 * For a given key, runs the generator associated with it, either injecting
	 * data if it's specified (and not null or false) or just running the
	 * generator. If we are intending on reading or already have output to be
	 * read then we return false and don't run the gen.
	 * This is equivalent to blocking on a read or write.
	 *
	 * @param type $key
	 * @param type $readLine
	 * @return boolean
	 */
	protected function runGen($key, $readLine)
	{
		$gen = $this->commandGenerators[$key];

		if( $readLine !== false && $readLine !== null )
			$genResponse = $gen->send( $readLine );
		elseif( ! $this->buffers[$key]->isBlocked() )
		{
			$gen->next();
			$genResponse = $gen->current();
		}
		else $genResponse = false;

		return $genResponse;
	}

	/**
	 * Handles a generator response with a given key, by either setting our
	 * intent to read, or by adding our output to the output buffer, unless we
	 * are the last process in the system in which case we output our response.
	 *
	 * @param string|ReadIntent $genResponse
	 * @param integer $key
	 */
	protected function handleGenResponse($genResponse, $key)
	{
		if( $genResponse instanceof ReadIntent )
			$this->readIntents[$key] = true;
		else
		{
			// If we are the last command in the chain and we want to write,
			// then we can write to the stdOut, whatever that is defined as.
			if( $this->commands[$key] === end($this->commands) )
				$this->stdOut->write( $genResponse );
			else
				$this->buffers[$key]->write( $genResponse );
		}
	}
}
