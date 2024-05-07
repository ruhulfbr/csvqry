<?php

/**
 *
 * src/Closure/Conditions/NotEqualsDate.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery\Closure\Conditions;

class NotEqualsDate implements ClosureInterface
{
    /**
     *
     * @param $value // The first date value to compare.
     * @param $valueToCompare // The second date value to compare.
     * @param $dateFormat // (Optional) The format to use for date comparison. Default is 'Y-m-d'.
     *
     * @return bool Returns true if the two date values are equal, false otherwise.
     */
    public function match($value, $valueToCompare, $dateFormat = null): bool
    {
        $valueDate = \DateTimeImmutable::createFromFormat($dateFormat ?: 'Y-m-d', $value);
        $valueToCompareDate = \DateTimeImmutable::createFromFormat($dateFormat ?: 'Y-m-d', $valueToCompare);

        return $valueDate != $valueToCompareDate;
    }
}
