<?php

/**
 * src/Closure/JoinClosure.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 */

namespace Ruhul\CSVQuery\Closure;

class JoinClosure extends AbstractClosure
{
    /**
     * @param array $results
     * @param array $joinArray
     * @return array
     */
    public static function apply(array $results, array $joinArray): array
    {
        if ( !empty($joinArray) ) {
            foreach ($joinArray as $join) {
                $arrayToJoin = $join['array'];
                $arrayName = $join['arrayName'];
                $parentKey = $join['parentKey'];
                $foreignKey = $join['foreignKey'];

                foreach ($results as $key => $result) {
                    if (array_key_exists($parentKey, $result) && $arrayToJoin[$foreignKey] === $result[$parentKey]) {
                        $results[$key][$arrayName] = $arrayToJoin;
                    } else {
                        unset($results[$key]);
                    }
                }
            }
        }

        return $results;
    }
}
