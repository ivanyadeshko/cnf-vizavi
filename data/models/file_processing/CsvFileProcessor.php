<?php

namespace models\file_processing;


use common\BasicRequestModel;


abstract class CsvFileProcessor extends BasicRequestModel
{

    /**
     * @var MemoryFile|null
     */
    protected $memoryFile;


    /**
     * @var string
     */
    protected $csv_row_delimeter = ';';

    /**
     * @var string
     */
    protected $csv_row_enclosure = '"';


    public function load($date): void
    {
        $this->memoryFile = new MemoryFile($date);
    }


    public function validate(): bool
    {
        if (!$this->memoryFile || $this->memoryFile->getExtension() !== FileExtension::CSV) {
            $this->addError('Файл не загружен или имеет неверный формат');
            return false;
        }

        $this->memoryFile->rewind();
        if (!$columns = $this->parseRow($this->memoryFile->readNextRow())) {
            $this->addError('Ошибка чтения файла');
            return false;
        }
        if ($not_founded = array_diff($this->getModelRequiredColumns(), $columns)) {
            $this->addError('Колонка(и): "' . implode(",", $not_founded) . '" не найдены в файле');
            return false;
        }

        if (!$this->loadFile()) {
            $this->addError('Ошибка загрузки файла');
            return false;
        }


        return true;
    }



    protected function loadFile()
    {

        $this->memoryFile->rewind();


        $columns = $this->clearingColumns(
            $this->parseRow($this->memoryFile->readNextRow()),
            true
        );


        while (($row = $this->memoryFile->readNextRow()) !== null) {
            $values = $this->clearingColumns( $this->parseRow($row) );

            switch (count($columns) <=> count($values)) {
                case 1:
                    $values = array_pad($values, count($columns), '');
                    break;
                case -1:
                    $values = array_slice($values, 0, count($columns));
                    break;
            }

            $this->addToComposite( array_combine($columns, $values) );
        }

        $this->afterLoadFile();

        return true;
    }


    /**
     * @param string $row
     * @return array
     */
    protected function parseRow(string $row) : array
    {
        return str_getcsv($row, $this->csv_row_delimeter, $this->csv_row_enclosure);
    }

    /**
     * @param array $columns
     * @param bool $remove_empty
     * @return array
     */
    protected function clearingColumns(array $columns, $remove_empty = false) : array
    {
        $out = [];
        foreach ($columns AS $val) {
            $val = trim($val);
            if ($remove_empty && $val === '') {
                continue;
            }
            $out[] = $val;
        }
        return $out;
    }


    abstract function addToComposite(array $data) : void;

    /**
     * Return list of model required columns
     *
     * @return array
     */
    abstract function getModelRequiredColumns() : array;

    /**
     * Any process after loading all file models
     *
     * @return void
     */
    protected function afterLoadFile() : void
    {

    }
}