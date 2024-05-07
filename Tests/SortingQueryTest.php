<?php
/**
 *
 * Tests/SortingQueryTest.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Ruhul\CSVQuery\CSVQ;
use Ruhul\CSVQuery\Exceptions\InvalidSortingKeyException;
use Ruhul\CSVQuery\Exceptions\InvalidSortingOperatorException;
use Ruhul\CSVQuery\Exceptions\MultipleSortingOperationException;

class SortingQueryTest extends TestCase
{
    private string $_filePath = "Tests/files/data.csv";

    /**
     * @test Expect Exception InvalidSortingKeyException
     * @throws Exception
     */
    public function it_ThrowInvalidSortingKeyException()
    {
        $key = "ages";
        $message = "Invalid ordering/sorting operation key: `" . $key . "`.";

        $this->expectException(InvalidSortingKeyException::class);
        $this->expectExceptionMessage($message);

        CSVQ::from($this->_filePath)->orderBy($key, 'desc');
    }

    /**
     * @test Expect Exception InvalidSortingOperatorException
     * @throws Exception
     */
    public function it_ThrowInvalidSortingOperatorException()
    {
        $operator = "<===>";
        $message = "Invalid ordering/sorting operator: `" . $operator . "`.";

        $this->expectException(InvalidSortingOperatorException::class);
        $this->expectExceptionMessage($message);

        CSVQ::from($this->_filePath)->orderBy('id', $operator);
    }

    /**
     * @test Expect Exception MultipleSortingOperationException
     * @throws Exception
     */
    public function it_ThrowMultipleSortingOperationException()
    {
        $message = "Multiple ordering/sorting operations are not allowed.";

        $this->expectException(MultipleSortingOperationException::class);
        $this->expectExceptionMessage($message);

        CSVQ::from($this->_filePath)->orderBy('id', 'desc')->orderBy('age', 'asc');
    }

    /**
     * @test Expect results Sorting ASC
     * @throws Exception
     */
    public function it_shouldGetResultsSortedAsAscending()
    {
        $results = CSVQ::from($this->_filePath)->orderBy('id', 'asc')->get();

        $this->assertEquals(1, $results[0]['id']);
        $this->assertEquals('Allis', $results[0]['name']);
    }

    /**
     * @test Expect results Sorting DESC
     * @throws Exception
     */
    public function it_shouldGetResultsSortedAsDescending()
    {
        $results = CSVQ::from($this->_filePath)->orderBy('id', 'DESC')->get();

        $this->assertEquals(20, $results[0]['id']);
        $this->assertEquals('Ethan Hernandez', $results[0]['name']);
    }

    /**
     * @test Expect results Sorting Date ASC
     * @throws Exception
     */
    public function it_shouldGetResultsSortedAsDateAscending()
    {
        $results = CSVQ::from($this->_filePath)->orderBy('dob', 'asc')->get();

        $this->assertEquals(3, $results[0]['id']);
        $this->assertEquals('Sashenka', $results[0]['name']);
    }

    /**
     * @test Expect results Sorting Date DESC
     * @throws Exception
     */
    public function it_shouldGetResultsSortedAsDateDescending()
    {
        $results = CSVQ::from($this->_filePath)->orderBy('dob', 'desc')->get();

        $this->assertEquals(9, $results[0]['id']);
        $this->assertEquals('Ava Wilson', $results[0]['name']);
    }

    /**
     * @test Expect latest results
     * @throws Exception
     */
    public function it_shouldGetColumnWiseLatestResults()
    {
        $results = CSVQ::from($this->_filePath)->latest()->get();

        $this->assertEquals(20, $results[0]['id']);
        $this->assertEquals('Ethan Hernandez', $results[0]['name']);
    }

    /**
     * @test Expect Oldest results
     * @throws Exception
     */
    public function it_shouldGetColumnWiseOldestResults()
    {
        $results = CSVQ::from($this->_filePath)->oldest()->get();

        $this->assertEquals(1, $results[0]['id']);
        $this->assertEquals('Allis', $results[0]['name']);
    }

}
