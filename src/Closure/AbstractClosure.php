<?php

/**
 * src/Closure/AbstractClosure.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 */

namespace Ruhul\CSVQuery\Closure;

use Ruhul\CSVQuery\Exceptions\ColumnNotFoundException;

abstract class AbstractClosure
{
    public const ARRAY_SEPARATOR = '.';
    public const ALIAS_DELIMITER = ' as ';

    /**
     * Retrieve the value from an array element based on a given key.
     *
     * @param string $key The key to retrieve the value.
     * @param mixed $arrayElement The array element to search.
     *
     * @return mixed The value if found, or null otherwise.
     * @throws ColumnNotFoundException
     */
    protected static function getElementValue(string $key, array $arrayElement): mixed
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
