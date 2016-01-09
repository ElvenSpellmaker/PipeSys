<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\EOF;
use ElvenSpellmaker\PipeSys\IO\IOInterface;

/**
 * Specifies a method for reading data into the PipeSys.
 */
interface InputInterface extends IOInterface
{
	/**
	 * Reads data into the PipeSys.
	 *
	 * @return string|EOF The data to be used or an EOF object once the stream
	 * is exhausted.
	 */
	public function read();
}
