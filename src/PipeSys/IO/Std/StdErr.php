<?php

namespace ElvenSpellmaker\PipeSys\IO\Std;

use ElvenSpellmaker\PipeSys\IO\EOF;
use ElvenSpellmaker\PipeSys\IO\OutputInterface;

/**
 * Allows writing to the STDERR channel.
 */
class StdErr implements OutputInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function write($out)
	{
		if ($out instanceof EOF)
		{
			return false;
		}

		fwrite(STDERR, $out);
	}
}
