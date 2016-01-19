<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\AbstractCommand;
use ElvenSpellmaker\PipeSys\IO\EOF;
use ElvenSpellmaker\PipeSys\IO\OutputIntent;
use ElvenSpellmaker\PipeSys\IO\ReadIntent;

/**
 * A basic `grep` command.
 */
class Grep extends AbstractCommand
{
	/**
	 * @var string
	 */
	protected $regex;

	/**
	 * @param string $regex
	 */
	public function __construct($regex)
	{
		// Escape the % signs in the string.
		$regex = str_replace('%', '\%', $regex);
		$this->regex = "%$regex%";
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCommand()
	{
		while (true)
		{
			$input = (yield new ReadIntent);

			if ($input instanceof EOF)
			{
				break;
			}

			$input = rtrim($input, "\r\n");

			$output = preg_grep($this->regex, [$input]);

			if (count($output))
			{
				yield new OutputIntent("$input\n");
			}
		}
	}
}
