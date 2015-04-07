<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\OutputInterface;

class StdOut implements OutputInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function write($out)
	{
		fwrite( STDOUT, $out );
	}
}
