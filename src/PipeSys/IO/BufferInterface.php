<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\EOF;

interface BufferInterface
{
	/**
	 * Writes a line into the buffer if there is room.
	 *
	 * @param string|EOF $line
	 *
	 * @return bool Whether a successful write ocurred or not.
	 */
	public function write($line);

	/**
	 * Attempts to read a line from the buffer.
	 *
	 * @return string|EOF|false A string or EOF if successful, false on failure.
	 */
	public function read();
}
