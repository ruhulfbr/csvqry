<?php
/**
 *
 * Tests/LimitQueryTest.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Ruhul\CSVQuery\CSVQ;

class LimitQueryTest extends TestCase
{
    private string $_filePath = "Tests/files/data.csv";

    /**
     * @test Expect 3 Item of results
     * @throws Exception
     */
    public function it_shouldGetResultsWithLimit()
    {
        $results = CSVQ::from($this->_filePath)->limit(3)->get();
        $this->assertCount(3, $results);
    }

    /**
     * @test Expect 3 Item of results from index 2
     * @throws Exception
     */
    public function it_shouldGetResultsWithLimitAndOffset()
    {
        $results = CSVQ::from($this->_filePath)->limit(2, 1)->get();
        $this->assertCount(2, $results);
    }

    /**
     * @test Expect 0 Item of results with limit offset
     * @throws Exception
     */
    public function it_shouldGetZeroItemResultsWithLimitAndOffset()
    {
        $results = CSVQ::from($this->_filePath)->limit(200, 100)->get();
        $this->assertCount(0, $results);
    }

}
