<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\BufferFactoryInterface;
use ElvenSpellmaker\PipeSys\IO\QueueBuffer;

/**
 * Creates Buffer Queues for commands to write to and read from.
 */
class QueueBufferFactory implements BufferFactoryInterface
{
	/**
	 * @var integer
	 */
	private $queueLength;

	/**
	 * @param integer $queueLength
	 */
	public function __construct($queueLength = QueueBuffer::DEFAULT_BUFFER_LENGTH)
	{
		$this->queueLength = $queueLength;
	}

	/**
	 * {@inheritdoc}
	 */
	public function create()
	{
		return new QueueBuffer($this->queueLength);
	}
}
