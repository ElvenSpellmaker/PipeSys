<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\AbstractCommand;
use ElvenSpellmaker\PipeSys\IO\OutputIntent;

/**
 * Similar to the UNIX `yes` command.
 */
class Yes extends AbstractCommand
{
	/**
	 * @var string
	 */
	private $echoLine;

	/**
	 * @param string $echoLine
	 */
	public function __construct($echoLine = 'y')
	{
		$this->echoLine = $echoLine;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCommand()
	{
		while (true)
		{
			yield new OutputIntent($this->echoLine);
		}
	}
}
