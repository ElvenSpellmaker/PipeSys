#!/usr/bin/env php
<?php
/**
 * @title    Example to show small head into larger head.
 * @command  ChattyYes | head -n 5 | head
 * @expected Yes\nI\nAm\nChatty\nYes\n
 */

error_reporting(E_ALL);

include __DIR__ . '/../vendor/autoload.php';

use ElvenSpellmaker\PipeSys as PS;
use ElvenSpellmaker\PipeSys\Command as Command;

$c = new PS\Scheduler(new Command\StandardConnector);
$c->addCommand(new Command\ChattyYes);
$c->addCommand(new Command\Head(5));
$c->addCommand(new Command\Head(10));
$c->run();
