<?php

namespace ElvenSpellmaker\PipeSystem\IO;

use ElvenSpellmaker\PipeSystem\IO\OutputInterface;

class StdOut implements OutputInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function write($out)
	{
		fwrite(STDOUT, $out);
	}
}
