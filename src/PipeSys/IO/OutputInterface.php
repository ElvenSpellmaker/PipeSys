<?php

namespace ElvenSpellmaker\PipeSys\IO;

/**
 * Specifies a method for writing data to the end of the PipeSys.
 */
interface OutputInterface
{
	/**
	 * Writes the output of the PipeSys.
	 *
	 * @param mixed $out
	 */
	public function write($out);
}
