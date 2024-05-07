<?php

/**
 *
 * src/CSVQ.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery;

use Exception;
use Generator;
use Ruhul\CSVQuery\Exceptions\EmptyCsvFileException;
use Ruhul\CSVQuery\Exceptions\FileTypeNotAllowedException;
use Ruhul\CSVQuery\Exceptions\InvalidFilePathException;

class CSVQ extends Builder
{
    /**
     * @var string
     */
    private string $_filePath;

    /**
     * @var array
     */
    private array $_fields = [];

    /**
     * @var array
     */
    private array $_data = [];

    /**
     * CSVQ constructor.
     * @param string $filePath The path to the CSV file.
     * @throws Exception
     */
    public function __construct(string $filePath)
    {
        // Set the file path and extract CSV data
        $this->_filePath = $filePath;
        $this->extractCSVData();

        // Throw exception if the CSV file is empty
        if (empty($this->_fields) && empty($this->_data)) {
            throw new EmptyCsvFileException("The CSV file `" . $this->_filePath . "` is empty.");
        }

        parent::__construct($this->_data, $this->_fields);
    }

    /**
     * Creates a new instance of the class using data from a CSV file.
     *
     * This method initializes the class with data extracted from the specified CSV file.
     * It sets the file path, extracts data from the CSV file, and initializes the class instance
     * with the extracted data and predefined fields.
     *
     * @param string $filePath The path to the CSV file.
     * @return static A new instance of the class initialized with data from the CSV file.
     * @throws Exception
     */
    public static function from(string $filePath): static
    {
        return new static($filePath);
    }

    /**
     * @throws Exception
     */
    public function extractCSVData(): void
    {
        foreach ($this->parseCSV() as $rowData) {
            $this->_data[] = $rowData;
        }
    }


    /**
     * Extracts column names and data rows from the CSV file.
     *
     * @return Generator True if extraction is successful, false otherwise.
     * @throws Exception
     */
    private function parseCSV(): Generator
    {
        $this->validateFile();

        $handle = fopen($this->_filePath, 'r');

        $skipFirstRow = true;
        while (($row = fgetcsv($handle)) !== false) {
            // Skip the first row (column headers)
            if ($skipFirstRow) {
                $this->_fields = $row;
                $skipFirstRow = false;
                continue;
            }

            //$this->_data[] = $this->prepareDataArray($row);
            yield $this->prepareDataArray($row);
        }

        // Close the file handle
        fclose($handle);
    }

    /**
     * Extracts data from a row based on defined fields.
     *
     * @param array $row Input row to extract data from.
     * @return array Extracted data array.
     */
    private function prepareDataArray(array $row): array
    {
        $array = [];
        if (!empty($this->_fields)) {
            foreach ($this->_fields as $key => $field) {
                $array[$field] = $row[$key] ?? '';
            }
        }

        return $array;
    }

    /**
     * Validates the data source (CSV file path).
     *
     * @return void True if the file is valid, false otherwise.
     * @throws Exception
     */
    private function validateFile(): void
    {
        if (!file_exists($this->_filePath) || !is_file($this->_filePath) || !is_readable($this->_filePath)) {
            throw new InvalidFilePathException("Invalid or unreadable file path: " . $this->_filePath);
        }

        $extension = pathinfo($this->_filePath, PATHINFO_EXTENSION);
        if (strtolower($extension) !== 'csv') {
            throw new FileTypeNotAllowedException("File type not allowed: " . $extension);
        }
    }
}
