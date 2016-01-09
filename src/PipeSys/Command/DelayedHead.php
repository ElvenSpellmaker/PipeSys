<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\AbstractCommand;
use ElvenSpellmaker\PipeSys\IO\EOF;
use ElvenSpellmaker\PipeSys\IO\OutputIntent;
use ElvenSpellmaker\PipeSys\IO\ReadIntent;

/**
 * Similar to Head but delays by half its amount beforehand.
 */
class DelayedHead extends AbstractCommand
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
		$delay = $this->lines / 2;
		while ($delay--)
		{
			yield true;
		}

		while ($this->lines--)
		{
			$input = (yield new ReadIntent);
			if ($input instanceof EOF)
			{
				break;
			}

			yield new OutputIntent($input);
		}

		yield new OutputIntent(new EOF);
	}
}
