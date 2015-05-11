<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\EOF;
use ElvenSpellmaker\PipeSys\IO\OutputInterface;

/**
 * Emulates StdOut.
 */
class StdOut implements OutputInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function write($out)
	{
		if( $out instanceof EOF ) return false;

		fwrite( STDOUT, $out );
	}
}
