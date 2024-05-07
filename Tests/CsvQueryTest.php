<?php
/**
 *
 * Tests/CsvQueryTest.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Ruhul\CSVQuery\CSVQ;
use Ruhul\CSVQuery\Exceptions\InvalidDateStringException;
use Ruhul\CSVQuery\Exceptions\ColumnNotFoundException;
use Ruhul\CSVQuery\Exceptions\InvalidWhereOperatorException;

class CsvQueryTest extends TestCase
{
    private string $_filePath = "Tests/files/data.csv";

    /**
     * @test Expect Exception ColumnNotFoundException
     * @throws Exception
     */
    public function it_ThrowColumnNotFoundException()
    {
        $column = "nana";
        $message = "Unsupported column for SELECT : `" . $column . "`.";

        $this->expectException(ColumnNotFoundException::class);
        $this->expectExceptionMessage($message);

        CSVQ::from($this->_filePath)->select(['nana']);
    }

    /**
     * @test Expect Exception InvalidWhereKeyException
     * @throws Exception
     */
    public function it_ThrowExceptionInvalidWhereKey()
    {
        $key = "ages";
        $message = "Unsupported key for WHERE operation: `" . $key . "`.";

        $this->expectException(ColumnNotFoundException::class);
        $this->expectExceptionMessage($message);

        CSVQ::from($this->_filePath)->where($key, "=", 20);
    }

    /**
     * @test Expect Exception InvalidWhereOperatorException
     * @throws Exception
     */
    public function it_ThrowExceptionInvalidWhereOperator()
    {
        $operator = "<===>";
        $message = "Unsupported operator: " . $operator;

        $this->expectException(InvalidWhereOperatorException::class);
        $this->expectExceptionMessage($message);

        CSVQ::from($this->_filePath)->where('age', $operator, 20);
    }

    /**
     * @test Expect Exception InvalidDateStringException
     * @throws Exception
     */
    public function it_ThrowInvalidDateStringException()
    {
        $date = "555-555-555";
        $message = "Invalid date string, please provide a valid date.";

        $this->expectException(InvalidDateStringException::class);
        $this->expectExceptionMessage($message);

        CSVQ::from($this->_filePath)->whereDate('dob', "=", $date);
    }

    /**
     * @test Expect All Results
     * @throws Exception
     */
    public function it_shouldGetResultsAllData()
    {
        $qb = CSVQ::from($this->_filePath);
        $this->assertCount(20, $qb->all());
    }

    /**
     * @test Expect All Results with Selected Columns
     * @throws Exception
     */
    public function it_shouldGetResultsAllDataWithSelectedColumns()
    {
        $columns = ['id', 'name'];
        $results = CSVQ::from($this->_filePath)->select($columns)->get();
        $result = $results[0];

        $this->assertTrue(isset($result['id']));
        $this->assertTrue(isset($result['name']));
        $this->assertFalse(isset($result['age']));
    }

    /**
     * @test Expect All Results as where not applied
     * @throws Exception
     */
    public function it_shouldGetResultsWithNoWhereApplied()
    {
        $qb = CSVQ::from($this->_filePath);
        $this->assertCount(20, $qb->get());
    }

    /**
     * @test Expect One result
     * @throws Exception
     */
    public function it_shouldGetOneItemOfResult()
    {
        $result = CSVQ::from($this->_filePath)->row();
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Allis', $result['name']);
    }

    /**
     * @test Expect One result with where Condition
     * @throws Exception
     */
    public function it_shouldGetOneItemOfResultWithWhere()
    {
        $result = CSVQ::from($this->_filePath)->where('id', 1)->row();
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Allis', $result['name']);
    }

    /**
     * @test Expect Nth Item of result without where Condition
     * @throws Exception
     */
    public function it_shouldGetNthItemOfResult()
    {
        $result = CSVQ::from($this->_filePath)->getNth(1);
        $this->assertEquals(2, $result['id']);
        $this->assertEquals('Gwyneth', $result['name']);
    }

    /**
     * @test Expect Nth Item of result with where Condition
     * @throws Exception
     */
    public function it_shouldGetNthItemOfResultWithWhere()
    {
        $result = CSVQ::from($this->_filePath)->whereIn('id', [1, 2, 3])->getNth(2);

        $this->assertEquals(3, $result['id']);
        $this->assertEquals('Sashenka', $result['name']);
    }

    /**
     * @test Expect False when getting Nth Item of results
     * @throws Exception
     */
    public function it_shouldReturnFalseWhenGeNthItemOfResults()
    {
        $result = CSVQ::from($this->_filePath)->getNth(22);
        $this->assertFalse($result);
    }

    /**
     * @test Expect true when check has data
     * @throws Exception
     */
    public function it_shouldReturnTrueWhenCheckHasData()
    {
        $result = CSVQ::from($this->_filePath)->where('id', 1)->hasData();
        $this->assertTrue($result);
    }

    /**
     * @test Expect false when check has data
     * @throws Exception
     */
    public function it_shouldReturnFalseWhenCheckHasData()
    {
        $result = CSVQ::from($this->_filePath)->where('id', 100)->hasData();
        $this->assertFalse($result);
    }

    /**
     * @test Expect true when check doesExist
     * @throws Exception
     */
    public function it_shouldReturnTrueWhenCheckDoesExist()
    {
        $result = CSVQ::from($this->_filePath)->where('id', 1)->doesExist();
        $this->assertTrue($result);
    }

    /**
     * @test Expect false when check doesExist
     * @throws Exception
     */
    public function it_shouldReturnFalseWhenCheckDoesExist()
    {
        $result = CSVQ::from($this->_filePath)->where('id', 100)->doesExist();
        $this->assertFalse($result);
    }

    /**
     * @test Expect First item of result without apply where condition
     * @throws Exception
     */
    public function it_shouldGetFirstItemOfResultWithoutAnyWhereParam()
    {
        $result = CSVQ::from($this->_filePath)->first();
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Allis', $result['name']);
    }

    /**
     * @test Expect Last item of result without apply where condition
     * @throws Exception
     */
    public function it_shouldGetLastItemOfResultWithoutAnyWhereParam()
    {
        $result = CSVQ::from($this->_filePath)->last();
        $this->assertEquals(20, $result['id']);
        $this->assertEquals('Ethan Hernandez', $result['name']);
    }

    /**
     * @test Expect results with simple where condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithSimpleWhereCondition()
    {
        $results = CSVQ::from($this->_filePath)->where('id', 3)->get();
        $this->assertCount(1, $results);
    }

    /**
     * @test Expect results with greater than where condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithGreaterThanWhereCondition()
    {
        $results = CSVQ::from($this->_filePath)->where('id', '>', '2')->get();
        $this->assertCount(18, $results);
    }

    /**
     * @test Expect results with greater than equal where condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithGreaterThanEqualWhereCondition()
    {
        $results = CSVQ::from($this->_filePath)->where('id', '>=', 18)->get();
        $this->assertCount(3, $results);
    }

    /**
     * @test Expect results with less than where condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithLesThanWhereCondition()
    {
        $results = CSVQ::from($this->_filePath)->where('id', '<', 5)->get();
        $this->assertCount(4, $results);
    }

    /**
     * @test Expect results with less than equal where condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithLessThanEqualWhereCondition()
    {
        $results = CSVQ::from($this->_filePath)->where('id', '<=', 3)->get();
        $this->assertCount(3, $results);
    }

    /**
     * @test Expect results with not equal where condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithNotEqualWhereCondition()
    {
        $results = CSVQ::from($this->_filePath)->where('id', "!=", 3)->get();
        $this->assertCount(19, $results);
    }

    /**
     * @test Expect results with whereIn condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithWhereInCondition()
    {
        $results = CSVQ::from($this->_filePath)->whereIn('id', [1, 2])->get();
        $this->assertCount(2, $results);
    }

    /**
     * @test Expect results with whereNotIn condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithWhereNotInCondition()
    {
        $results = CSVQ::from($this->_filePath)->whereNotIn('id', [1, 2])->get();
        $this->assertCount(18, $results);
    }

    /**
     * @test Expect results with whereLke Contain (both) condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithWhereLikeContainCondition()
    {
        $results = CSVQ::from($this->_filePath)->whereLike('name', 'Smi')->get();
        $this->assertCount(2, $results);
    }

    /**
     * @test Expect results with whereLke contains with condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithWhereLikeStartWithCondition()
    {
        $results = CSVQ::from($this->_filePath)->whereLike('name', 'Mr', 'start')->get();
        $this->assertCount(1, $results);
    }

    /**
     * @test Expect results with whereLke End with condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithWhereLikeEndWithCondition()
    {
        $results = CSVQ::from($this->_filePath)->whereLike('name', 'Smith', 'end')->get();
        $this->assertCount(2, $results);
    }

    /**
     * @test Expect results with whereDate Equal condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithWhereDateEqualCondition()
    {
        $results = CSVQ::from($this->_filePath)->whereDate('dob', '1997-07-02')->get();
        $this->assertCount(1, $results);
    }

    /**
     * @test Expect results with whereDate Not Equal condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithWhereDateNotEqualCondition()
    {
        $results = CSVQ::from($this->_filePath)->whereDate('dob', '!=', '1997-07-02')->get();
        $this->assertCount(19, $results);
    }

    /**
     * @test Expect results with whereDate Greater Than condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithWhereDateGreaterThanCondition()
    {
        $results = CSVQ::from($this->_filePath)->whereDate('dob', '>', '1997-07-02')->get();
        $this->assertCount(3, $results);
    }

    /**
     * @test Expect results with whereDate Greater Than Equal condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithWhereDateGreaterThanEqualCondition()
    {
        $results = CSVQ::from($this->_filePath)->whereDate('dob', '>=', '1997-07-02')->get();
        $this->assertCount(4, $results);
    }

    /**
     * @test Expect results with whereDate Less Than condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithWhereDateLessThanCondition()
    {
        $results = CSVQ::from($this->_filePath)->whereDate('dob', '<', '1997-07-02')->get();
        $this->assertCount(16, $results);
    }

    /**
     * @test Expect results with whereDate Less Than Equal condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithWhereDateLessThanEqualCondition()
    {
        $results = CSVQ::from($this->_filePath)->whereDate('dob', '<=', '1997-07-02')->get();
        $this->assertCount(17, $results);
    }

    /**
     * @test Expect results with where and Or_Where condition
     * @throws Exception
     */
    public function it_shouldGetResultsWithWhereAndOrWhereEqualCondition()
    {
        $results = CSVQ::from($this->_filePath)->where('id', 1)->orWhere('id', 3)->get();
        $this->assertCount(2, $results);
    }

}
