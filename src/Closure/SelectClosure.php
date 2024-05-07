<?php

/**
 * src/Closure/SelectClosure.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 */

namespace Ruhul\CSVQuery\Closure;

class SelectClosure extends AbstractClosure
{
    /**
     * Apply where filtering to the array.
     *
     * @param array $data The array to filter.
     * @param array $columns
     * @return array The filtered array.
     */
    public static function apply(array $data, array $columns): array
    {
        if (in_array('*', $columns)) {
            return $data;
        }

        if (!empty($data)) {
            return array_map(function ($item) use ($columns) {
                return array_intersect_key($item, array_flip($columns));
            }, $data);
        }

        return $data;
    }
}
