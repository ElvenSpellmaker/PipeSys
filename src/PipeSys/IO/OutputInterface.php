<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\IOInterface;

/**
 * Specifies a method for writing data to the end of the PipeSys.
 */
interface OutputInterface extends IOInterface
{
	/**
	 * Writes the output of the PipeSys.
	 *
	 * @param mixed $line
	 */
	public function write($line);
}
