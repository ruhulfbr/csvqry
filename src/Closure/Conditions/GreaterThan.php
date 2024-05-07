<?php

/**
 *
 * src/Closure/Conditions/GreaterThan.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery\Closure\Conditions;

class GreaterThan implements ClosureInterface
{
    /**
     *
     * @param $value
     * @param $valueToCompare
     * @param null $dateFormat
     * @return bool
     *
     */
    public function match($value, $valueToCompare, $dateFormat = null): bool
    {
        return $value > $valueToCompare;
    }
}
