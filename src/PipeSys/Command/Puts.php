<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\CommandInterface;

class Puts implements CommandInterface
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
	public function doCommand()
	{
		yield $this->echoLine;
	}
}
