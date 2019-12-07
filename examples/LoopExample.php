#!/usr/bin/env php
<?php
/**
 * @title    Shows handling of falsy data such as `0` in a loop.
 * @command  yes 0 | grep 0 | head
 * @expected 0\n0\n0\n0\n0\n0\n0\n0\n0\n0\n
 */

error_reporting(E_ALL);

include __DIR__ . '/../vendor/autoload.php';

use ElvenSpellmaker\PipeSys as PS;
use ElvenSpellmaker\PipeSys\Command as Command;
use ElvenSpellmaker\PipeSys\IO as IO;

class LoopSystem extends Command\AbstractCommand
{
	private $i = 5;

	public function getCommand()
	{
		while($this->i--)
		{
			yield new IO\OutputIntent('0');
			echo (yield new IO\ReadIntent), "\n";
		}
	}
}

$loopBuffer = new IO\QueueBuffer;

$start = new LoopSystem;
$end = new LoopSystem;

$start->setStdIn($loopBuffer);
$end->setStdOut($loopBuffer);

$c = new PS\Scheduler(new Command\StandardConnector);
$c->addCommand($start);
$c->addCommand($end);
$c->run();
