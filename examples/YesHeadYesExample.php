#!/usr/bin/env php
<?php
/**
 * @title    Tests two infintie commands sandwiched by a terminating command.
 * @command  yes | head | yes
 * @expected y\ny\ny\ny\ny\ny\ny\ny\ny\ny\n
 */

error_reporting(E_ALL);

include __DIR__ . '/../vendor/autoload.php';

use ElvenSpellmaker\PipeSys as PS;
use ElvenSpellmaker\PipeSys\Command as Command;

$c = new PS\Scheduler(new Command\StandardConnector);
$c->addCommand(new Command\Yes);
$c->addCommand(new Command\Head(1));
$c->addCommand(new Command\Yes);

$output = '';

if ($count = count($c->run(3)) !== 3)
{
	$output .= "Expecting 3 elements, received $count\n";
}

$expected = [
	0 => 'ElvenSpellmaker\PipeSys\Command\Yes',
	2 => 'ElvenSpellmaker\PipeSys\Command\Yes',
];
$received = array_map('get_class', $c->run(1));

if ($received !== $expected)
{
	$output .= "Expecting the head to be missing!\n";
}

$expected = [2 => 'ElvenSpellmaker\PipeSys\Command\Yes'];
$received = array_map('get_class', $c->run(1));

if ($received !== $expected)
{
	$output .= "Expecting the last yes to be present only!\n";
}

if (! count($c->run(5)))
{
	$output .= "Command has terminated sometime before 10 runs!\n";
}

echo $output;
