<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\Std\StdConsts;

/**
 * Commands yield this to signal their intent to output something.
 */
class OutputIntent
{
	/**
	 * @var mixed
	 */
	private $output;

	/**
	 * @var integer
	 */
	private $channel;

	/**
	 * @param mixed   $output
	 * @param integer $channel
	 */
	public function __construct(
		$output,
		$channel = StdConsts::STDOUT
	)
	{
		$this->output = $output;
		$this->channel = $channel;
	}

	/**
	 * Gets the content of the Intent.
	 *
	 * @return mixed
	 */
	public function getContent()
	{
		return $this->output;
	}

	/**
	 * Returns the channel of the Intent.
	 *
	 * @return integer
	 */
	public function getChannel()
	{
		return $this->channel;
	}
}
