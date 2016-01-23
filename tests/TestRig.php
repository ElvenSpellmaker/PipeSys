#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/FileDocSplitter.php';

$fi = new FilesystemIterator(
	__DIR__ . '/../examples/',
	FilesystemIterator::CURRENT_AS_PATHNAME | FilesystemIterator::SKIP_DOTS
);

$docSplitter = new FileDocSplitter;

$command = 1;
$exit = 0;
$php = escapeshellarg(PHP_BINARY);
$exitCode = 0;
foreach($fi as $file)
{
	list($title, $command, $expected) = parseDocBlock($file);

	echo "$title - $command\n";

	$file = escapeshellarg($file);

	ob_start();

	system("timeout 10s $php $file", $exitCode);

	$output = ob_get_contents();
	ob_end_clean();

	$pass = (str_replace('\n', "\n", $expected) === $output) && $exitCode === 0;

	if (! $pass)
	{
		echo "$output\n";
		$exit = 1;
	}

	$string = $pass
		? "\e[0;32mPASS\e[0m"
		: "\e[0;31mFAIL\e[0m";

	echo "$string\n";

	$command++;
}

exit($exit);

function parseDocBlock($file)
{
	global $docSplitter;

	$tags = $docSplitter->getTags($file);

	if (count($tags) !== 3)
	{
		throw new RuntimeException('Incorrect Docblock found in example: "' . basename($file) . '"');
	}

	$expectedTags = [
		'title',
		'command',
		'expected',
	];

	foreach ($tags as $key => $tag)
	{
		if ($tags[$key]->getName() !== $expectedTags[$key])
		{
			throwTagException($expectedTags[$key], $file);
		}
	}

	return [$tags[0]->getContent(), $tags[1]->getContent(), $tags[2]->getContent()];
}

function throwTagException($tagName, $file)
{
	throw new RuntimeException('Tagname "' . $tagName . '" not found in example: "' . basename($file) . '"');
}
