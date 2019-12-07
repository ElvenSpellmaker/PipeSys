<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\BufferInterface;
use RuntimeException;
use SplQueue;

/**
 * A buffer implemented as a queue.
 */
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
	 * @var boolean
	 */
	protected $isValid = true;

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
		$this->checkValidity();

		if ($this->isWritable())
		{
			$this->buffer->enqueue($line);

			return true;
		}

		if (! isset($this->block))
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
		return count($this->buffer) < $this->bufferLength;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isBlocked()
	{
		return isset($this->block);
	}

	/**
	 * {@inheritdoc}
	 */
	public function read()
	{
		if ($this->buffer->count() === 0)
		{
			$this->checkValidity();
		}

		try
		{
			$ret = $this->buffer->dequeue();
		}
		catch (RuntimeException $e)
		{
			return null;
		}

		if (isset($this->block))
		{
			$this->buffer->enqueue($this->block);
			unset($this->block);
		}

		return $ret;
	}

	/**
	 * {@inheritdoc}
	 */
	public function invalidate()
	{
		$this->isValid = false;
	}

	/**
	 * Checks if the buffer is valid and throws an exception if not.
	 *
	 * @throws InvalidBufferException
	 */
	final private function checkValidity()
	{
		if (! $this->isValid)
		{
			throw new InvalidBufferException;
		}
	}
}
