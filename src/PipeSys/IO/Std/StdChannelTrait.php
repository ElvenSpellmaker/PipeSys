<?php

namespace ElvenSpellmaker\PipeSys\IO;

use ElvenSpellmaker\PipeSys\IO\IOInterface;
use ElvenSpellmaker\PipeSys\IO\IOConstants;

/**
 * Helpers for IOableTraits to use to define Standard Channel methods.
 */
trait StdChannelTrait
{
	/**
	 * Sets the Standard Intput channel.
	 *
	 * @return $this
	 */
	public function setStdIn(InputInterface $input)
	{
		return $this->setIOChannel($input, IOConstants::IO_STDIN);
	}

	/**
	 * Sets the Standard Output channel.
	 *
	 * @return $this
	 */
	public function setStdOut(OutputInterface $output)
	{
		return $this->setIOChannel($output, IOConstants::IO_STDOUT);
	}

	/**
	 * Sets the Standard Error channel.
	 *
	 * @return $this
	 */
	public function setStdErr(OutputInterface $output)
	{
		return $this->setIOChannel($output, IOConstants::IO_STDERR);
	}

	/**
	 * Gets the Standard Input channel.
	 *
	 * @return IOInterface
	 */
	public function getStdIn()
	{
		return $this->getIOChannel(IOConstants::IO_STDIN);
	}

	/**
	 * Gets the Standard Output channel.
	 *
	 * @return IOInterface
	 */
	public function getStdOut()
	{
		return $this->getIOChannel(IOConstants::IO_STDOUT);
	}

	/**
	 * Gets the Standard Error channel.
	 *
	 * @return IOInterface
	 */
	public function getStdErr()
	{
		return $this->getIOChannel(IOConstants::IO_STDERR);
	}
}
