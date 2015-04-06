<?php

namespace ElvenSpellmaker\PipeSystem\Command;

use ElvenSpellmaker\PipeSystem\Command\CommandInterface;

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
