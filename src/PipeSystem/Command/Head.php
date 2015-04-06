<?php

namespace ElvenSpellmaker\PipeSystem\Command;

use ElvenSpellmaker\PipeSystem\Command\CommandInterface;
use ElvenSpellmaker\PipeSystem\IO\EOF;
use ElvenSpellmaker\PipeSystem\IO\ReadIntent;

class Head implements CommandInterface
{
	protected $lines;

	public function __construct($lines = 10)
	{
		$this->lines = (int)$lines;
	}

	/**
	 * {@inheritdoc}
	 */
	public function doCommand()
	{
		while($this->lines--)
		{
			$input = (yield new ReadIntent);
			if( $input instanceof EOF ) break;
			yield $input;
		}
	}
}