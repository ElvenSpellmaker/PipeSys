<?php

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag;

/**
 * Methods for dealing with File Docblocks.
 */
class FileDocSplitter
{
	/**
	 * Gets the first PHP Docblock from a file and returns the parsed Docblock tags.
	 *
	 * @var string $filename
	 *
	 * @return Tag
	 *
	 * @throws RuntimeException
	 */
	public function getTags($filename)
	{
		$file = file_get_contents($filename);
		$matches = [];

		preg_match('%(/\*\*.+?\*/)%s', $file, $matches);

		if (! $matches)
		{
			throw new RuntimeException('Couldn\'t locate the file docblock!');
		}

		return (new DocBlock($matches[1]))->getTags();
	}
}
