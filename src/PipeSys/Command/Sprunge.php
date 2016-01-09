<?php

namespace ElvenSpellmaker\PipeSys\Command;

use ElvenSpellmaker\PipeSys\Command\AbstractCommand;
use ElvenSpellmaker\PipeSys\IO\EOF;
use ElvenSpellmaker\PipeSys\IO\OutputIntent;
use ElvenSpellmaker\PipeSys\IO\ReadIntent;

/**
 * Reads from StdIn, and then shunts the text to sprunge reutrning the URL.
 */
class Sprunge extends AbstractCommand
{
	/**
	 * @var string
	 */
	private $text;

	/**
	 * {@inheritdoc}
	 */
	public function getCommand()
	{
		while (true)
		{
			$input = (yield new ReadIntent);

			if ($input instanceof EOF)
			{
				break;
			}

			$this->text .= $input;
		}

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://sprunge.us');
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, ['sprunge' => $this->text]);

		$result = curl_exec($curl);

		curl_close($curl);

		yield new OutputIntent($result);
	}
}
