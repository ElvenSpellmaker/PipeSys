<?php

namespace ElvenSpellmaker\PipeSystem\IO;

/**
 * Specifies a method for writing data to the end of the PipeSystem.
 */
interface OutputInterface
{
	/**
	 * Writes the output of the PipeSystem.
	 *
	 * @param mixed $out
	 */
	public function write($out);
}
