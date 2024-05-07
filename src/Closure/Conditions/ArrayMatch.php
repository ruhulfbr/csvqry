<?php

/**
 *
 * src/Closure/Conditions/ArrayMatch.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery\Closure\Conditions;

class ArrayMatch implements ClosureInterface
{
    /**
     *
     * @param $value           // The first set of values to compare.
     * @param $valueToCompare  // The second set of values to compare against.
     * @param $dateFormat      // (Optional) The format to use for date comparison, if applicable.
     *
     * @return bool Returns true if there is any intersection between the two sets of values, false otherwise.
     */
    public function match($value, $valueToCompare, $dateFormat = null): bool
    {
        return (bool) count(array_intersect($value, $valueToCompare));
    }
}
