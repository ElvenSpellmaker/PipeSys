<?php

namespace ElvenSpellmaker\PipeSys\IO;

/**
 * Used to yield an error out of a command.
 */
class Error
{
	/**
	 * @var string
	 */
	protected $errorString;

	public function __construct($errorString)
	{
		$this->errorString = (string)$errorString;
	}

	public function getErrorString()
	{
		return $this->errorString;
	}
}
