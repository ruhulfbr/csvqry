<?php
/**
 *
 * Tests/AggregateQueryTest.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Ruhul\CSVQuery\CSVQ;
use Ruhul\CSVQuery\Exceptions\InvalidAggregateColumnException;

class AggregateQueryTest extends TestCase
{
    private string $_filePath = "Tests/files/data.csv";

    /**
     * @test Expect Exception InvalidAggregateColumnException
     * @throws Exception
     */
    public function it_ThrowExceptionInvalidAggregateColumn()
    {
        $column = "ages";
        $message = "Unsupported Aggregate Columns: `" . $column . "`.";

        $this->expectException(InvalidAggregateColumnException::class);
        $this->expectExceptionMessage($message);

        CSVQ::from($this->_filePath)->min($column);
    }

    /**
     * @test Expect Counted Number from Result
     * @throws Exception
     */
    public function it_shouldReturnCountOfResult()
    {
        $total = CSVQ::from($this->_filePath)->count();
        $this->assertEquals(20, $total);
    }

    /**
     * @test Expect Counted Number from Result With Where
     * @throws Exception
     */
    public function it_shouldReturnCountOfResultWithWhere()
    {
        $total = CSVQ::from($this->_filePath)->whereIn('id', [1, 2, 3])->count();
        $this->assertEquals(3, $total);
    }

    /**
     * @test Expect Return sum of age
     * @throws Exception
     */
    public function it_shouldReturnSUMOfAge()
    {
        $sum = CSVQ::from($this->_filePath)->whereIn('id', [1, 2, 3])->sum('age');
        $this->assertEquals(109, $sum);
    }

    /**
     * @test Expect Return Average of age
     * @throws Exception
     */
    public function it_shouldReturnAverageOfAge()
    {
        $avg = CSVQ::from($this->_filePath)->whereIn('id', [1, 2, 3])->avg('age');
        $this->assertEquals(36, intval($avg));
    }

    /**
     * @test Expect Return result Minimum age
     * @throws Exception
     */
    public function it_shouldReturnResultWithMinimumAge()
    {
        $result = CSVQ::from($this->_filePath)->whereIn('id', [1, 2, 3])->min('age');
        $this->assertEquals(1, $result['id']);
    }

    /**
     * @test Expect Return result Maximum age
     * @throws Exception
     */
    public function it_shouldReturnResultWithMAXAge()
    {
        $result = CSVQ::from($this->_filePath)->whereIn('id', [1, 2, 3])->max('age');
        $this->assertEquals(3, $result['id']);
    }
}
