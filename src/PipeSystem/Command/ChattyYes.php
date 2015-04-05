<?php

namespace ElvenSpellmaker\PipeSystem\Command;

use ElvenSpellmaker\PipeSystem\Command\CommandInterface;

class ChattyYes implements CommandInterface
{
	protected $chatting = [
		"Yes\n",
		"I\n",
		"Am\n",
		"Chatty\n",
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
