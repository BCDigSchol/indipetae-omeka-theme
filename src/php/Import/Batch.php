<?php

namespace BCLib\Indipetae\Import;

/**
 * A batch of letters for import
 *
 * The Batch is a replacement for an iterable array of letters that retains the Letter type, adding
 * some safety.
 *
 * @package BCLib\Indipetae\Import
 */
class Batch implements \Iterator
{
    /** @var Letter[] */
    private $letters;

    private $position = 0;

    public function add(Letter $letter): void
    {
        $this->letters[] = $letter;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->letters[$this->position];
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return isset($this->letters[$this->position]);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->position = 0;
    }
}