<?php

namespace ElvenSpellmaker\PipeSys\Command;

use Generator;

/**
 * Commands should produce a generator for use with PipeSys.
 */
interface CommandInterface
{
	/**
	 * Sets up a generator for the command.
	 *
	 * @return Generator
	 */
	public function doCommand();
}
