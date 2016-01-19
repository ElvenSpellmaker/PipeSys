<?php
/**
 * @title    Example to show large head into smaller head.
 * @command  ChattyYes | head -n 200 | head -n 7
 * @expected Yes\nI\nAm\nChatty\nYes\nI\nAm\n
 */

error_reporting(E_ALL);

include __DIR__ . '/../vendor/autoload.php';

use ElvenSpellmaker\PipeSys as PS;
use ElvenSpellmaker\PipeSys\Command as Command;

$c = new PS\Scheduler(new Command\StandardConnector);
$c->addCommand(new Command\ChattyYes);
$c->addCommand(new Command\Head(200));
$c->addCommand(new Command\Head(7));
$c->run();
