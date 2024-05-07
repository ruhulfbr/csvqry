<?php

/**
 *
 * Tests/UtilityTest.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */
namespace Ruhul\CSVQuery\Tests;
use PHPUnit\Framework\TestCase;

class UtilityTest extends TestCase
{
    /**
     * @test converting a plain array to an array of stdClass objects.
     */
    public function it_plainArrayToObjectArray()
    {
        $array = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'rating' => 4,
                'company' => "Example Company"
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'rating' => 5,
                'company' => "Another Example Company"
            ]
        ];


        $objectArray = convertToObjectArray($array);
        foreach ($objectArray as $item) {
            $this->assertInstanceOf(\stdClass::class, $item);
        }
    }

    /**
     * @test converting an array of stdClass objects to a plain array.
     */
    public function it_objectArrayToPlainArray()
    {
        $a = new \stdClass();
        $a->id = 10;
        $a->name = 'John Doe';
        $a->email = 'john.doe@example.com';

        $b = new \stdClass();
        $b->id = 20;
        $b->name = 'Jane Smith';
        $b->email = 'jane.smith@example.com';

        $array = [$a, $b];

        $plainArray = convertToPlainArray($array);
        foreach ($plainArray as $item) {
            $this->assertTrue(is_array($item));
        }
    }
}
