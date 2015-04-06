<?php

namespace ElvenSpellmaker\PipeSystem\IO;

use ElvenSpellmaker\PipeSystem\IO\EOF;
use ElvenSpellmaker\PipeSystem\IO\InputInterface;

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
