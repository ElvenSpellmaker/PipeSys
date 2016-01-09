<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\AbstractCommand;
use ElvenSpellmaker\PipeSys\IO\OutputIntent;

/**
 * An `echo` command called Puts due to PHP Lexer limitations.
 */
class Puts extends AbstractCommand
{
	/**
	 * @var string
	 */
	protected $echoLine;

	/**
	 * @param string $echoLine
	 */
	public function __construct($echoLine)
	{
		$this->echoLine = $echoLine . PHP_EOL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCommand()
	{
		yield new OutputIntent($this->echoLine);
	}
}
