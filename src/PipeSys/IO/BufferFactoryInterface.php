<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\BufferInterface;

/**
 * Buffer Factories create Buffers! Yay!
 */
interface BufferFactoryInterface
{
	/**
	 * Creates a Buffer.
	 *
	 * @return BufferInterface
	 */
	public function create();
}
