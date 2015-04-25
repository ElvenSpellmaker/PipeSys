<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\CommandInterface;

/**
 * ChattyYes outputs a few phrases over and over, similar to yes, but with
 * multiple phrases.
 */
class ChattyYes implements CommandInterface
{
	protected $chatting = [];

	public function __construct()
	{
		$this->chatting = [
			'Yes'. PHP_EOL,
			'I'. PHP_EOL,
			'Am'. PHP_EOL,
			'Chatty'. PHP_EOL,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function doCommand()
	{
		$i = -1;
		while(true) yield ($this->chatting[$i = ++$i % 4]);
	}
}
