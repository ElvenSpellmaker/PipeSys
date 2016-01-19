<?php
/**
 * @title    Tests two infinite commands and a terminating command.
 * @command  yes | yes | head
 * @expected y\ny\ny\ny\ny\ny\ny\ny\ny\ny\n
 */

error_reporting(E_ALL);

include __DIR__ . '/../vendor/autoload.php';

use ElvenSpellmaker\PipeSys as PS;
use ElvenSpellmaker\PipeSys\Command as Command;

$c = new PS\Scheduler(new Command\StandardConnector);
$c->addCommand(new Command\Yes);
$c->addCommand(new Command\Yes);
$c->addCommand(new Command\Head);
$c->run();
