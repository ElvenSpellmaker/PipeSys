<?php
/**
 * @title    Basic example to show grepping.
 * @command  ChattyYes | grep 'Yes\|Chatty' | head
 * @expected Yes\nChatty\nYes\nChatty\nYes\nChatty\nYes\nChatty\nYes\nChatty\n
 */

error_reporting(E_ALL);

include __DIR__ . '/../vendor/autoload.php';

use ElvenSpellmaker\PipeSys as PS;
use ElvenSpellmaker\PipeSys\Command as Command;

$c = new PS\Scheduler(new Command\StandardConnector);
$c->addCommand(new Command\ChattyYes);
$c->addCommand(new Command\Grep('Yes|Chatty'));
$c->addCommand(new Command\Head);
$c->run();
