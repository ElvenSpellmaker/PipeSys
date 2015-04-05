<?php

namespace ElvenSpellmaker\PipeSystem\Command;

use ElvenSpellmaker\PipeSystem\Command\CommandInterface;

class Yes implements CommandInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function doCommand($echo = '')
	{
		! $echo and $echo = 'y';

		$echo .= "\n";

		while(true) yield $echo;
	}
}
