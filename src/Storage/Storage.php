<?php

namespace Vinecave\B2BTask\Storage;

use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Reader;
use Iterator;
use League\Csv\Writer;

class Storage
{
    private Reader $reader;

    private Writer $writer;

    /**
     * @throws Exception
     */
    public function __construct(
        string $filename
    ) {
        $this->reader = Reader::createFromPath($filename)->setHeaderOffset(0);
        $this->writer = Writer::createFromPath($filename);
    }

    public function readRows(): Iterator
    {
        return $this->reader->getRecords();
    }

    /**
     * @throws CannotInsertRecord
     */
    public function insertRow(array $row): void
    {
        if (empty($this->reader->getHeader())) {
            $this->writer->insertOne(
                array_keys($row)
            );
        }

        $this->writer->insertOne($row);
    }
}
