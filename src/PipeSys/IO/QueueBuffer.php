<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\BufferInterface;
use RuntimeException;
use SplQueue;

class QueueBuffer implements BufferInterface
{
	const DEFAULT_BUFFER_LENGTH = 65535;

	/**
	 * @var SplQueue
	 */
	protected $buffer;

	/**
	 * @var integer
	 */
	protected $bufferLength;

	/**
	 * @param integer $bufferLength
	 */
	public function __construct($bufferLength = self::DEFAULT_BUFFER_LENGTH)
	{
		$this->buffer = new SplQueue;
		$this->bufferLength = $bufferLength;
	}

	/**
	 * {@inheritdoc}
	 */
	public function write($line)
	{
		if( count( $this->buffer ) < $this->bufferLength )
		{
			$this->buffer->enqueue( $line );

			return true;
		}

		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function read()
	{
		try
		{
			return $this->buffer->dequeue();
		}
		catch(RuntimeException $e)
		{
			return false;
		}
	}
}
