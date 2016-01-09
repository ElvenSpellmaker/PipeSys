<?php

namespace ElvenSpellmaker\PipeSys\Command;

//use ElvenSpellmaker\PipeSys\Command\AbstractCommand;

interface ConnectorInterface
{
	/**
	 * Connects commands together using Inputs and Outputs.
	 *
	 * @param array $commands
	 * #@param AbstractCommand[] $commands <-- Bug in the sniffer
	 *
	 * @return void
	 */
	public function connect(array $commands);
}
