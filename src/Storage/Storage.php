<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Storage;

use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Reader;
use Iterator;
use League\Csv\SyntaxError;
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
        $this->reader = Reader::createFromPath($filename, 'r')->setHeaderOffset(0);
        $this->writer = Writer::createFromPath($filename, 'a+');
    }

    /**
     * @return Iterator|array
     */
    public function readRows(): Iterator|array
    {
        try {
            return $this->reader->getRecords();
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @throws CannotInsertRecord
     */
    public function insertRow(array $row): void
    {
        $h = '';

        try {
            $h = $this->reader->getHeader();
//            print_r($h); die();
        } catch (SyntaxError $syntaxError) {
            $this->writer->insertOne(
                array_keys($row)
            );
        }
//        print_r($h); die();


        $this->writer->insertOne(array_values($row));
    }
}
