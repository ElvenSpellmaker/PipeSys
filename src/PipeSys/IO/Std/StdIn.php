<?php

namespace ElvenSpellmaker\PipeSys\IO\Std;

use ElvenSpellmaker\PipeSys\IO\EOF;
use ElvenSpellmaker\PipeSys\IO\InputInterface;

/**
 * Allows reading from the STDIN channel in PHP.
 */
class StdIn implements InputInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function read()
	{
		$input = fgets(STDIN);
		return ($input === false) ? new EOF : $input;
	}
}
