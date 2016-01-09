<?php

namespace ElvenSpellmaker\PipeSys\IO\Std;

use ElvenSpellmaker\PipeSys\IO\InputInterface;
use ElvenSpellmaker\PipeSys\IO\OutputInterface;
use ElvenSpellmaker\PipeSys\IO\Std\StdConsts;

/**
 * Provides helper methods for IOables that want StdIO channels.
 */
trait StdIoTrait
{
	/**
	 * Sets the Standard In Channel for this IOable.
	 *
	 * @param InputInterface $input
	 *
	 * @return $this
	 */
	public function setStdIn(InputInterface $input)
	{
		return $this->setIOChannel($input, StdConsts::STDIN);
	}

	/**
	 * Sets the Standard Out Channel for this IOable.
	 *
	 * @param OutputInterface $output
	 *
	 * @return $this
	 */
	public function setStdOut(OutputInterface $output)
	{
		return $this->setIOChannel($output, StdConsts::STDOUT);
	}

	/**
	 * Sets the Standard Error Channel for this IOable.
	 *
	 * @param OutputInterface $output
	 *
	 * @return $this
	 */
	public function setStdErr(OutputInterface $output)
	{
		return $this->setIOChannel($output, StdConsts::STDERR);
	}

	/**
	 * Gets the Standard In Channel for this IOable.
	 *
	 * @return $this
	 */
	public function getStdIn()
	{
		return $this->getIOChannel(StdConsts::STDIN);
	}

	/**
	 * Gets the Standard Output Channel for this IOable.
	 *
	 * @return $this
	 */
	public function getStdOut()
	{
		return $this->getIOChannel(StdConsts::STDOUT);
	}

	/**
	 * Gets the Standard Error Channel for this IOable.
	 *
	 * @return $this
	 */
	public function getStdErr()
	{
		return $this->getIOChannel(StdConsts::STDERR);
	}
}
