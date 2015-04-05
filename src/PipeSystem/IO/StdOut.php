<?php

namespace ElvenSpellmaker\PipeSystem\IO;

class StdOut
{
	public function write($out)
	{
		fwrite(STDOUT, $out);
	}
}
