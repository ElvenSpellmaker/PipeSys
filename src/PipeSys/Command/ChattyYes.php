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
			'Yes' . PHP_EOL,
			'I' . PHP_EOL,
			'Am' . PHP_EOL,
			'Chatty' . PHP_EOL,
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
