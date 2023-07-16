<?php

namespace Junyang7\PhpCommon;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class _XlsxWriter
{

    private $writer;
    private $sheet;

    public function __construct()
    {

        $this->writer = WriterEntityFactory::createXLSXWriter();

    }

    public function open($file)
    {

        $this->writer->openToFile($file);

    }

    public function setCurrentSheetByIndex($index = 0)
    {

        foreach ($this->writer->getSheets() as $sheet) {
            if ($sheet->getIndex() === $index) {
                $this->sheet = $sheet;
                $this->writer->setCurrentSheet($this->sheet);
                return;
            }
        }

    }

    public function setCurrentSheetByName($name = "Sheet1")
    {

        foreach ($this->writer->getSheets() as $sheet) {
            if ($sheet->getName() === $name) {
                $this->sheet = $sheet;
                $this->writer->setCurrentSheet($this->sheet);
                return;
            }
        }

    }

    public function write($row)
    {

        $this->writer->addRow(WriterEntityFactory::createRowFromArray($row));

    }

    public function close()
    {

        $this->writer->close();

    }

}
