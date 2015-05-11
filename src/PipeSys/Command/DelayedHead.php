<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\CommandInterface;
use ElvenSpellmaker\PipeSys\IO\EOF;
use ElvenSpellmaker\PipeSys\IO\ReadIntent;

class DelayedHead implements CommandInterface
{
	protected $lines;

	/**
	 * @param string $lines
	 */
	public function __construct($lines = 10)
	{
		$this->lines = (int)$lines;
	}

	/**
	 * {@inheritdoc}
	 */
	public function doCommand()
	{
		$delay = $this->lines;
		while($delay--) yield 'hello';

		while($this->lines--)
		{
			$input = (yield new ReadIntent);
			if( $input instanceof EOF ) break;
			yield $input;
		}
	}
}
