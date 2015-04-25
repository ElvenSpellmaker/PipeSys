<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\CommandInterface;

/**
 * Yes just constantly outputs one thing to its StdOut.
 */
class Yes implements CommandInterface
{
	const ECHO_LINE = 'y'. PHP_EOL;

	/**
	 * {@inheritdoc}
	 */
	public function doCommand()
	{
		while(true) yield static::ECHO_LINE;
	}
}
