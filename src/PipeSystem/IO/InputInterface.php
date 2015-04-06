<?php

namespace ElvenSpellmaker\PipeSystem\IO;

use ElvenSpellmaker\PipeSystem\IO\EOF;

/**
 * Specifies a method for reading data into the PipeSystem.
 */
interface InputInterface
{
	/**
	 * Reads data into the PipeSystem.
	 *
	 * @return string|EOF The data to be used or an EOF object once the stream
	 * is exhausted.
	 */
	public function read();
}
