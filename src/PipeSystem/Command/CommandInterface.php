<?php

namespace ElvenSpellmaker\PipeSystem\Command;

use Generator;

interface CommandInterface
{
	/**
	 * Sets up a generator for the command.
	 *
	 * @return Generator
	 */
	public function doCommand();
}