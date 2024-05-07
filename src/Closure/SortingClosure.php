<?php

/**
 * src/Closure/SortingClosure.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 */

namespace Ruhul\CSVQuery\Closure;

use Ruhul\CSVQuery\Exceptions\ColumnNotFoundException;

class SortingClosure
{
    public const ARRAY_SEPARATOR = '.';

    /**
     * @var array
     */
    public static array $operatorsMap = [
        'ASC',
        'DESC',
        'asc',
        'desc'
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

    /**
     * Retrieve the value from an array element based on a given key.
     *
     * @param string $key The key to retrieve the value.
     * @param mixed $arrayElement The array element to search.
     *
     * @return mixed The value if found, or null otherwise.
     * @throws ColumnNotFoundException
     */
    private static function getElementValue(string $key, array $arrayElement): mixed
    {
        return self::getValueFromKeys(
            explode(self::ARRAY_SEPARATOR, $key), $arrayElement
        );
    }

    /**
     * Retrieve the value from an array element based on a given array of keys.
     *
     * @param array $keysArray The array of keys to retrieve the value.
     * @param array $arrayElement The array element to search.
     *
     * @return mixed The value if found.
     *
     * @throws ColumnNotFoundException If the key is not found in the array.
     */
    private static function getValueFromKeys(array $keysArray, array $arrayElement): mixed
    {
        if (count($keysArray) > 1) {
            $key = array_shift($keysArray);

            return self::getValueFromKeys($keysArray, (array)$arrayElement[$key]);
        }

        if (!isset($arrayElement[$keysArray[0]])) {
            throw new ColumnNotFoundException("The key `" . $keysArray[0] . "` is invalid within the data.");
        }

        return $arrayElement[$keysArray[0]];
    }
}
