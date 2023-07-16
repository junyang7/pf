<?php

namespace Junyang7\PhpCommon;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class _XlsxReader
{

    private $reader;
    private $sheet;

    public function __construct()
    {

        $this->reader = ReaderEntityFactory::createXLSXReader();
        $this->reader->setShouldFormatDates(true);

    }

    public function open($file)
    {

        $this->reader->open($file);
        $this->setCurrentSheetByIndex(0);

    }

    public function setCurrentSheetByIndex($index = 0)
    {

        foreach ($this->reader->getSheetIterator() as $sheet) {
            if ($sheet->getIndex() === $index) {
                $this->sheet = $sheet;
                return;
            }
        }

    }

    public function setCurrentSheetByName($name = "Sheet1")
    {

        foreach ($this->reader->getSheetIterator() as $sheet) {
            if ($sheet->getName() === $name) {
                $this->sheet = $sheet;
                return;
            }
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
