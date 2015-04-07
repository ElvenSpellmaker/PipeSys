<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\EOF;
use ElvenSpellmaker\PipeSys\IO\InputInterface;

class StdIn implements InputInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function read()
	{
		$input = fgets( STDIN );
		return ( $input === false ) ? new EOF : $input;
	}
}
