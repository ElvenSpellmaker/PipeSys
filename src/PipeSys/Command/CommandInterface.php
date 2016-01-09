<?php

namespace ElvenSpellmaker\PipeSys\Command;

use Generator;

interface CommandInterface
{
	/**
	 * Sets up a generator for the command.
	 *
	 * @return Generator
	 */
	public function getCommand();

	/**
	 * Runs the command until the next yield if not blocked.
	 *
	 * @return boolean|null True if the command ran, false if it's blocked and
	 * null if it's terminating.
	 */
	public function runOnce();
}
