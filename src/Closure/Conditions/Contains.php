<?php

/**
 *
 * src/Closure/Conditions/Contains.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery\Closure\Conditions;

class Contains implements ClosureInterface
{
    /**
     *
     * @param $value           // The string to search within.
     * @param $valueToCompare  // The string to search for.
     * @param $dateFormat // (Optional) The format to use for date comparison, if applicable.
     *
     * @return bool Returns true if $value contains $valueToCompare, false otherwise.
     */
    public function match($value, $valueToCompare, $dateFormat = null): bool
    {
        return str_contains($value, $valueToCompare);
    }

}
