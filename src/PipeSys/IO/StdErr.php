<?php

namespace ElvenSpellmaker\PipeSys\IO;

/**
 * Emulates StdErr
 */
class StdErr implements OutputInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function write($out)
	{
		if( $out instanceof EOF ) return false;

		fwrite( STDERR, $out );
	}
}
