<?php

namespace ElvenSpellmaker\PipeSystem\Command;

use ElvenSpellmaker\PipeSystem\Command\CommandInterface;

class ChattyYes implements CommandInterface
{
	protected $chatting = [
		'Yes'. PHP_EOL,
		'I'. PHP_EOL,
		'Am'. PHP_EOL,
		'Chatty'. PHP_EOL,
	];

	/**
	 * {@inheritdoc}
	 */
	public function doCommand()
	{
		$i = -1;
		while(true) yield ($this->chatting[$i = ++$i % 4]);
	}
}
