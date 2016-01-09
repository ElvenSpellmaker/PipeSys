<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\IOConstants;

/**
 * Commands yield these to signal they want to read.
 */
class ReadIntent
{
	/**
	 * @var integer
	 */
	protected $readChannel;

	/**
	 * @param integer $channel
	 */
	public function __construct($channel = IOConstants::IO_STDIN)
	{
		$this->readChannel = $channel;
	}

	/**
	 * Gets the channel to read from.
	 *
	 * @return integer
	 */
	public function getChannel()
	{
		return $this->readChannel;
	}
}
