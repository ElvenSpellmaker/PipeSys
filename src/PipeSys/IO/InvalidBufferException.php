<?php

namespace ElvenSpellmaker\PipeSys\IO;

use Exception;

/**
 * Exceptions thrown by Invalid Buffers if they are accessed for writing or
 * reading when empty.
 */
class InvalidBufferException extends Exception
{
}
