<?php

namespace Junyang7\PhpCommon;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class _CsvReader
{

    private $reader;
    private $sheet;

    public function __construct()
    {

        $this->reader = ReaderEntityFactory::createCSVReader();

    }

    public function open($file)
    {

        $this->reader->open($file);
        foreach ($this->reader->getSheetIterator() as $sheet) {
            $this->sheet = $sheet;
            return;
        }

    }

    public function read()
    {

        foreach ($this->sheet->getRowIterator() as $row) {
            yield $row->toArray();
        }

    }

    public function close()
    {

        $this->reader->close();

    }

}
