<?php

/**
 * src/utility/helper.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 */

if (!function_exists('toArray'))
{
    /**
     *
     * @param mixed $data
     *
     * @return array|null
     */
    function toArray(mixed $data): ?array
    {
        if (is_array($data)) {
            return $data;
        }

        if (is_object($data)) {
            return (array) $data;
        }

        return null;
    }
}

if (!function_exists('convertToObjectArray'))
{
    /**
     *
     * @param array $array
     * @return array
     */
    function convertToObjectArray(array $array): array
    {
        $objectArray = [];
        foreach ($array as $item) {
            $objectArray[] = (object) $item;
        }
        return $objectArray;
    }
}

if (!function_exists('convertToPlainArray'))
{
    /**
     *
     * @param array $array
     * @return array
     */
    function convertToPlainArray(array $array): array
    {
        $objectArray = [];
        foreach ($array as $item) {
            $objectArray[] = (array) $item;
        }
        return $objectArray;
    }
}

if (!function_exists('isAssociativeArray'))
{
    /**
     * @param array $array
     *
     * @return bool
     */
    function isAssociativeArray(array $array): bool
    {
        if ([] === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }
}


if (!function_exists('isDateString'))
{
    /**
     *
     * @param string $dateString
     * @return bool
     */
    function isDateString(string $dateString): bool
    {
        return strtotime($dateString) !== false;
    }
}

if (!function_exists('pr'))
{
    /**
     * @param $data
     */

    function pr($data)
    {
        echo "<pre>";
        print_r($data);
        exit();
    }
}