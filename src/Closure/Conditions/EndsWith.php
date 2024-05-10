<?php

/**
 *
 * src/Closure/Conditions/EndsWith.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery\Closure\Conditions;

class EndsWith implements ClosureInterface
{
    /**
     *
     * @param $value           // The string to compare the end of.
     * @param $valueToCompare  // The string to compare against.
     * @param $dateFormat // (Optional) The format to use for date comparison, if applicable.
     *
     * @return bool Returns true if the end of $value matches $valueToCompare, false otherwise.
     */
    public function match($value, $valueToCompare, $dateFormat = null): bool
    {
        return str_ends_with($value, $valueToCompare);
    }

}
