<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\AbstractCommand;
use ElvenSpellmaker\PipeSys\IO\EOF;
use ElvenSpellmaker\PipeSys\IO\OutputIntent;
use ElvenSpellmaker\PipeSys\IO\ReadIntent;

/**
 * Similar to the UNIX `head` command.
 */
class Head extends AbstractCommand
{
	/**
	 * @var integer
	 */
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
	public function getCommand()
	{
		$input = null;
		while ($this->lines-- !== 0)
		{
			$input = (yield new ReadIntent);
			if ($input instanceof EOF)
			{
				break;
			}

			yield new OutputIntent($input);
		}

		yield $input;
	}
}
