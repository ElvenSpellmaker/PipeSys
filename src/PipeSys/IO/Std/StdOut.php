<?php

namespace ElvenSpellmaker\PipeSys\IO\Std;

use ElvenSpellmaker\PipeSys\IO\EOF;
use ElvenSpellmaker\PipeSys\IO\OutputInterface;

/**
 * Standard Out which writes to the STDOUT of PHP.
 */
class StdOut implements OutputInterface
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

		fwrite(STDOUT, $out);
	}
}
