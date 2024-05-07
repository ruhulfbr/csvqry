<?php

/**
 * src/Closure/WhereClosure.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 */

namespace Ruhul\CSVQuery\Closure;

use Ruhul\CSVQuery\Closure\Conditions\ClosureInterface;

class WhereClosure extends AbstractClosure
{
    /**
     * Apply where filtering to the array.
     *
     * @param array $data The array to filter.
     * @param array $where The array of where Params.
     * @param array $orWhere
     * @return array The filtered array.
     */
    public static function apply(array $data, array $where, array $orWhere = []): array
    {
        if (empty($where) && empty($orWhere)) {
            return $data;
        }

        return array_filter($data, function ($element) use ($where, $orWhere) {
            $matchesWhere = self::matchesConditions($element, $where);

            if (!$matchesWhere && !empty($orWhere)) {
                return self::matchesConditions($element, $orWhere);
            }

            return $matchesWhere;
        });
    }

    /**
     * Maps the operator to its corresponding filter class name.
     *
     * @param string $operator The operator for which to determine the filter class name.
     * @return string|null The name of the filter class corresponding to the operator, or null if not found.
     */
    public static function getFilterClass(string $operator): string|null
    {
        return match ($operator) {
            '=' => 'Equals',
            '===' => 'EqualsStrict',
            '!=' => 'NotEquals',
            '!==' => 'NotEqualsStrict',
            '>' => 'GreaterThan',
            '>=' => 'GreaterThanEquals',
            '<' => 'LessThan',
            '<=' => 'LessThanEquals',
            'like_both' => 'Contains',
            'like_start' => 'StartsWith',
            'like_end' => 'EndsWith',
            'IN_ARRAY' => 'InArray',
            'NOT_IN_ARRAY' => 'NotInArray',
            '=_DATE' => 'EqualsDate',
            '!=_DATE' => 'NotEqualsDate',
            '>_DATE' => 'GreaterThanDate',
            '>=_DATE' => 'GreaterThanEqualsDate',
            '<_DATE' => 'LessThanDate',
            '<=_DATE' => 'LessThanEqualsDate',
            'ARRAY_MATCH' => 'ArrayMatch',
            default => null
        };
    }

    /**
     * Checks if the provided operator is valid.
     *
     * @param string $operator The operator to validate.
     * @return bool True if the operator is valid, false otherwise.
     */
    public static function isValidOperator(string $operator): bool
    {
        return self::getFilterClass($operator) !== null;
    }


    /** ========= Private Methods ============ */


    /**
     * Checks if the given element matches all conditions.
     *
     * @param array $element The element to check against the conditions.
     * @param array $conditions An array of conditions to be matched against the element.
     * @return bool Returns true if all conditions are met by the element, otherwise false.
     */
    private static function matchesConditions(array $element, array $conditions): bool
    {
        foreach ($conditions as $param) {
            if (isset($element[$param['key']]) && !self::checkCondition($param, $element)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Apply a given WHERE condition to an array element.
     *
     * @param array $where The WHERE condition to apply.
     * @param array $element The array element to apply the condition to.
     * @return bool
     */
    private static function checkCondition(array $where, array $element): bool
    {
        $value = $element[$where['key']] ?? '';
        $filterClass = "Ruhul\\CSVQuery\\Closure\\Conditions\\" . self::getFilterClass($where['operator']);

        /** @var ClosureInterface $filter */
        $filter = new $filterClass();

        return $filter->match($value, $where['value'], $where['date_format'] ?? null);
    }
}
