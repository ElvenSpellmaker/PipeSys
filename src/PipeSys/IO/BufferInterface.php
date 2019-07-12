<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\EOF;
use ElvenSpellmaker\PipeSys\IO\InputInterface;
use ElvenSpellmaker\PipeSys\IO\OutputInterface;

interface BufferInterface extends InputInterface, OutputInterface
{
	/**
	 * Writes a line into the buffer if there is room.
	 *
	 * @param string|EOF $line
	 *
	 * @return boolean Whether a successful write ocurred or not.
	 */
	public function write($line);

	/**
	 * Returns true if writing won't block.
	 *
	 * @return boolean True if writing to this buffer should succeed.
	 */
	public function isWritable();

	/**
	 * Returns true if the pipe is blocked.
	 *
	 * @returns boolean True if the pipe is blocked.
	 */
	public function isBlocked();

	/**
	 * Attempts to read a line from the buffer.
	 *
	 * @return string|EOF|false A string or EOF if successful, false on failure.
	 */
	public function read();

	/**
	 * Invalidates this buffer for writing.
	 */
	public function invalidate();
}
