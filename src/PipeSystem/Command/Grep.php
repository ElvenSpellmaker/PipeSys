<?php

namespace ElvenSpellmaker\PipeSystem\Command;

use ElvenSpellmaker\PipeSystem\Command\CommandInterface;
use ElvenSpellmaker\PipeSystem\IO\EOF;
use ElvenSpellmaker\PipeSystem\IO\ReadIntent;

class Grep implements CommandInterface
{
	/**
	 * @var string
	 */
	protected $regex = '';

	/**
	 * @param string $regex
	 */
	public function __construct($regex)
	{
		// Escape the % signs in the string.
		$regex = str_replace( '%', '\%', $regex );
		$this->regex = "%$regex%";
	}

	/**
	 * {@inheritdoc}
	 */
	public function doCommand()
	{
		while( true )
		{
			$input = (yield new ReadIntent);

			if( $input instanceof EOF ) break;

			$input = rtrim( $input, "\r\n" );

			$output = preg_grep( $this->regex, [$input] );

			if( count( $output ) ) yield $input . PHP_EOL;
		}
	}
}
