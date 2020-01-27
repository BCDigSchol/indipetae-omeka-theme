<?php

namespace BCLib\Indipetae\Import;

/**
 * Write out all the letters in a batch to something
 *
 * @package BCLib\Indipetae\Import
 */
interface BatchWriter
{
    /**
     * Write the letters
     *
     * @param Batch $batch
     */
    public function write(Batch $batch): void;

    /**
     * Get stats about what was written
     *
     * @return string
     */
    public function stats(): string;
}