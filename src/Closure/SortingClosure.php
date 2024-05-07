<?php

/**
 * src/Closure/SortingClosure.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 */

namespace Ruhul\CSVQuery\Closure;

use Ruhul\CSVQuery\Exceptions\ColumnNotFoundException;

class SortingClosure extends AbstractClosure
{
    /**
     * Mapping of supported sorting operators.
     *
     * This array maps supported sorting operators to their corresponding values.
     * These operators are used to specify the sorting order in queries or operations.
     * Supported operators include ASC (ascending), DESC (descending), DATE_ASC (ascending by date),
     * and DATE_DESC (descending by date).
     *
     * @var array
     */
    public static array $operatorsMap = [
        'ASC',
        'DESC',
        'DATE_ASC',
        'DATE_DESC',
    ];

    /**
     * @param array $results The array of results to filter and sort.
     * @param array $sortArray The array specifying the sorting criteria.
     *
     * @return array The filtered and sorted results array.
     * @throws ColumnNotFoundException
     */
    public static function apply(array $results, array $sortArray): array
    {
        if (!empty($sortArray)) {
            return self::sort($results, $sortArray);
        }

        return $results;
    }

    /**
     * Checks if the provided sorting operator is valid.
     *
     * @param string $operator The sorting operator to validate.
     * @return bool True if the operator is valid, false otherwise.
     */
    public static function isValidOperator(string $operator): bool
    {
        $operator = strtoupper($operator);
        return in_array($operator, self::$operatorsMap);
    }


    /**
     * Sorts the results array based on the provided sorting criteria.
     *
     * This method sorts the results array based on the specified key and order in the sorting criteria.
     * Optionally, it supports sorting dates based on a specified date format.
     *
     * @param array $results The array of results to sort.
     * @param array $sortingArray The array specifying the sorting criteria.
     *
     * @return array The sorted results array.
     * @throws ColumnNotFoundException
     */
    private static function sort(array $results, array $sortingArray): array
    {
        usort($results, function ($first, $second) use ($sortingArray) {
            $valueA = self::getElementValue($sortingArray['key'], $first);
            $valueB = self::getElementValue($sortingArray['key'], $second);

            if (isset($sortingArray['format'])) {
                $valueA = \DateTimeImmutable::createFromFormat(($sortingArray['format']) ?: 'Y-m-d', $valueA);
                $valueB = \DateTimeImmutable::createFromFormat(($sortingArray['format']) ?: 'Y-m-d', $valueB);
            }

            if ($valueA == $valueB) {
                return 0;
            }

            return ($valueA < $valueB) ? -1 : 1;
        });

        if ($sortingArray['order'] === 'DESC' || $sortingArray['order'] === 'DATE_DESC') {
            return array_reverse($results);
        }

        return $results;
    }
}
