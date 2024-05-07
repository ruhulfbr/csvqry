<?php

/**
 *
 * src/Closure/Conditions/ClosureInterface.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery\Closure\Conditions;

interface ClosureInterface
{
    /**
     *
     * @param $value
     * @param $valueToCompare
     * @param $dateFormat
     *
     * @return mixed Returns true if the values match, false otherwise.
     */

    public function match($value, $valueToCompare, $dateFormat = null): mixed;

}
