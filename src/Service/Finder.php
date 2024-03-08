<?php

namespace App\Service;

use Symfony\Component\Finder\SplFileInfo;

class Finder implements \IteratorAggregate, \Countable
{
    public const IGNORE_VCS_FILES = 1;
    public const IGNORE_DOT_FILES = 2;
    public const IGNORE_VCS_IGNORED_FILES = 4;

    private int $ignore = 0;

    public function __construct()
    {
        $this->ignore = static::IGNORE_VCS_FILES | static::IGNORE_DOT_FILES;
    }

    /**
     * Returns an Iterator for the current Finder configuration.
     *
     * This method implements the IteratorAggregate interface.
     *
     * @return \Iterator<string, SplFileInfo>
     *
     * @throws \LogicException if the in() method has not been called
     */
    public function getIterator(): \Traversable
    {
        return $this;
    }

    public function count(): int
    {
        return count($this);
    }
}
