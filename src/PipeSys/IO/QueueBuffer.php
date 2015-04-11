<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\BufferInterface;
use RuntimeException;
use SplQueue;

class QueueBuffer implements BufferInterface
{
	const DEFAULT_BUFFER_LENGTH = 65536;

	/**
	 * @var SplQueue
	 */
	protected $buffer;

	/**
	 * @var string
	 */
	protected $block;

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
		if( $this->isWritable() )
		{
			$this->buffer->enqueue( $line );

			return true;
		}

		if( ! isset( $this->block ) )
		{
			$this->block = $line;

			return true;
		}

		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isWritable()
	{
		return count( $this->buffer ) < $this->bufferLength;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isBlocked()
	{
		return isset( $this->block );
	}

	/**
	 * {@inheritdoc}
	 */
	public function read()
	{
		try
		{
			$ret = $this->buffer->dequeue();
		}
		catch(RuntimeException $e)
		{
			return false;
		}

		if( isset( $this->block ) )
		{
			$this->buffer->enqueue( $this->block );
			unset( $this->block );
		}

		return $ret;
	}
}
