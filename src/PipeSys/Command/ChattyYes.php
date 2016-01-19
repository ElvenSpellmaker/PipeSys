<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\AbstractCommand;
use ElvenSpellmaker\PipeSys\IO\OutputIntent;

/**
 * Chatty Yes is a `yes`-style command that says more than yes.
 */
class ChattyYes extends AbstractCommand
{
	/**
	 * @var string[]
	 */
	protected $chatting = [];

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->chatting = [
			"Yes\n",
			"I\n",
			"Am\n",
			"Chatty\n",
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCommand()
	{
		$i = -1;
		while (true)
		{
			yield new OutputIntent($this->chatting[$i = ++$i % 4]);
		}
	}
}
