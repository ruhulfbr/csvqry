<?php

/**
 *
 * src/Closure/Conditions/Equals.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery\Closure\Conditions;

class Equals implements ClosureInterface
{
    /**
     *
     * @param $value
     * @param $valueToCompare
     * @param $dateFormat // (Optional) The format to use for date comparison, if applicable.
     *
     * @return bool Returns true if $value is equal to $valueToCompare, false otherwise.
     */
    public function match($value, $valueToCompare, $dateFormat = null): bool
    {
        return $value == $valueToCompare;
    }

}
