<?php

/**
 * src/Closure/LimitClosure.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 */

namespace Ruhul\CSVQuery\Closure;

class LimitClosure
{
    /**
     * Filters the results based on a limit array.
     *
     * This method applies a limit to the results array if a limit array is provided.
     * The limit array specifies the indices to keep from the results array.
     *
     * @param array $results The array of results to filter.
     * @param array $limitArray The array specifying the indices to keep.
     *
     * @return array The filtered results array.
     */

    public static function apply(array $results, array $limitArray): array
    {
        if (!empty($limitArray)) {
            return self::slice($results, $limitArray);
        }

        return $results;
    }

    /**
     * Slices the results array based on the provided limit array.
     *
     * This method slices the results array based on the offset and length specified
     * in the limit array.
     *
     * @param array $results The array of results to slice.
     * @param array $limit The array specifying the offset and length of the slice.
     *
     * @return array The sliced results array.
     */
    private static function slice(array $results, array $limit): array
    {
        return array_slice($results, $limit['offset'], $limit['length']);
    }

}
