<?php
/**
 *
 * Tests/CsvFileTest.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Ruhul\CSVQuery\CSVQ;
use Ruhul\CSVQuery\Exceptions\EmptyCsvFileException;
use Ruhul\CSVQuery\Exceptions\EmptyCsvHeaderException;
use Ruhul\CSVQuery\Exceptions\FileTypeNotAllowedException;
use Ruhul\CSVQuery\Exceptions\InvalidFilePathException;

class CsvFileTest extends TestCase
{
    private string $_filePath = "Tests/files/data.csv";

    /**
     * @test Expect Exception InvalidFilePathException
     * @throws Exception
     */
    public function it_ThrowExceptionInvalidFilePath()
    {
        $filePath = 'Tests/files/data/non_existent_file.csv';

        $this->expectException(InvalidFilePathException::class);
        $this->expectExceptionMessage("Invalid or unreadable file path: " . $filePath);

        CSVQ::from($filePath);
    }

    /**
     * @test Expect Exception FileTypeNotAllowedException
     * @throws Exception
     */
    public function it_ThrowExceptionFileTypeNotAllowed()
    {
        $filePath = 'Tests/files/data.json';

        $this->expectException(FileTypeNotAllowedException::class);
        $this->expectExceptionMessage("File type not allowed: json");

        CSVQ::from($filePath);
    }

    /**
     * @test Expect Exception EmptyCsvHeaderException
     * @throws Exception
     */
    public function it_ThrowEmptyCsvHeaderException()
    {
        $filePath = 'Tests/files/data-empty-header.csv';

        $this->expectException(EmptyCsvHeaderException::class);
        $this->expectExceptionMessage("CSV header is empty, the first row consider as header/columns. `" . $filePath . "`");

        CSVQ::from($filePath);
    }

    /**
     * @test Expect Exception EmptyCsvFileException
     * @throws Exception
     */
    public function it_ThrowExceptionEmptyCsvFile()
    {
        $filePath = 'Tests/files/data-empty.csv';

        $this->expectException(EmptyCsvFileException::class);
        $this->expectExceptionMessage("No data found in the CSV file. `" . $filePath . "`");

        CSVQ::from($filePath);
    }
}
