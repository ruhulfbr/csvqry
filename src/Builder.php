<?php

/**
 *
 * src/Builder.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 *
 */

namespace Ruhul\CSVQuery;

use Exception;
use Ruhul\CSVQuery\Closure\JoinClosure;
use Ruhul\CSVQuery\Closure\LimitClosure;
use Ruhul\CSVQuery\Closure\SortingClosure;
use Ruhul\CSVQuery\Closure\SelectClosure;
use Ruhul\CSVQuery\Closure\WhereClosure;
use Ruhul\CSVQuery\Exceptions\InvalidAggregateColumnException;
use Ruhul\CSVQuery\Exceptions\InvalidDateStringException;
use Ruhul\CSVQuery\Exceptions\InvalidSortingKeyException;
use Ruhul\CSVQuery\Exceptions\ColumnNotFoundException;
use Ruhul\CSVQuery\Exceptions\InvalidSortingOperatorException;
use Ruhul\CSVQuery\Exceptions\InvalidWhereOperatorException;
use Ruhul\CSVQuery\Exceptions\MultipleSortingOperationException;

abstract class Builder
{

    /**
     * @var array
     */
    private array $_columns = ["*"];

    /**
     * @var array
     */
    private array $_where = [];

    /**
     * @var array
     */
    private array $_or_where = [];

    /**
     * @var array
     */
    private array $_order = [];

    /**
     * @var array
     */
    private array $_limit = [];

    /**
     * @var array
     */
    private array $_join = [];

    /**
     * Constructor to initialize the class instance with an array.
     */
    public function __construct(private array $_data, private array $_fields)
    {

    }

    /**
     * Create a new instance of the class from the provided array.
     *
     * @param array $data
     * @param array $fields
     * @return static Returns a new instance of the class.
     */
    public static function init(array $data, array $fields): static
    {
        return new static($data, $fields);
    }

    /**
     * Retrieve the filtered and sorted results.
     *
     * @return array The filtered and sorted results.
     * @throws ColumnNotFoundException
     */
    public function all(): array
    {
        return SortingClosure::apply($this->_data, $this->_order);
    }

    /**
     * Retrieve the filtered and sorted results.
     *
     * @return array The filtered and sorted results.
     * @throws ColumnNotFoundException
     */
    public function get(): array
    {
        if (empty($this->_data)) {
            return [];
        }

        $results = JoinClosure::apply($this->_data, $this->_join);
        $results = WhereClosure::apply($results, $this->_where, $this->_or_where);
        $results = LimitClosure::apply($results, $this->_limit);
        $results = SortingClosure::apply($results, $this->_order);
        return SelectClosure::apply($results, $this->_columns);
    }

    /**
     * Retrieve the first result from the filtered and sorted results.
     *
     * @return array The first result from the filtered and sorted results, or an empty array if no results are found.
     * @throws ColumnNotFoundException
     */
    public function row(): array
    {
        return $this->first();
    }

    /**
     * Retrieve the first result from the filtered and sorted results.
     *
     * @return array The first result from the filtered and sorted results, or an empty array if no results are found.
     * @throws ColumnNotFoundException
     */
    public function first(): array
    {
        $results = $this->get();
        return $results[0] ?? [];
    }

    /**
     * Get the last result from the filtered and sorted results.
     *
     * @return mixed The last result or an empty array if no results are found.
     * @throws ColumnNotFoundException
     */
    public function last(): array
    {
        $results = $this->get();
        $count = count($results);
        return $count > 0 ? $results[$count - 1] : [];
    }

    /**
     * Apply sorting to the query result.
     *
     * @param string $column The column/key to sort by.
     * @return $this Returns the instance of the class to allow method chaining.
     * @throws InvalidSortingOperatorException|Exceptions\MultipleSortingOperationException|InvalidSortingKeyException If the provided sorting operator is invalid.
     *
     */
    public function latest(string $column = 'id'): static
    {
        return $this->orderBy($column, 'desc');
    }

    /**
     * Apply sorting to the query result.
     *
     * @param string $column The column/key to sort by.
     * @return $this Returns the instance of the class to allow method chaining.
     * @throws InvalidSortingOperatorException|Exceptions\MultipleSortingOperationException|InvalidSortingKeyException If the provided sorting operator is invalid.
     *
     */
    public function oldest(string $column = 'id'): static
    {
        return $this->orderBy($column, 'ASC');
    }

    /**
     * Retrieve the first result from the filtered and sorted results.
     *
     * @return array|bool The first result from the filtered and sorted results, or an empty array if no results are found.
     * @throws ColumnNotFoundException
     */
    public function getNth(int $index): array|bool
    {
        $results = $this->get();
        return !empty($results[$index]) ? $results[$index] : false;
    }

    /**
     * Retrieve the first result from the filtered and sorted results.
     *
     * @return bool The first result from the filtered and sorted results, or an empty array if no results are found.
     * @throws ColumnNotFoundException
     */
    public function hasData(): bool
    {
        return !empty($this->row());
    }

    /**
     * Retrieve the first result from the filtered and sorted results.
     *
     * @return bool The first result from the filtered and sorted results, or an empty array if no results are found.
     * @throws ColumnNotFoundException
     */
    public function doesExist(): bool
    {
        return !empty($this->row());
    }

    /**
     * Get the count of results.
     *
     * @return int The count of results.
     * @throws ColumnNotFoundException
     */
    public function count(): int
    {
        return count($this->get());
    }

    /**
     * Calculate the sum of values for a specific key in the array.
     *
     * @param string $column The key for which to calculate the sum.
     * @return int|float The sum of values for the specified key.
     * @throws ColumnNotFoundException|InvalidAggregateColumnException If the specified key is not present in the array.
     */
    public function sum(string $column): int|float
    {
        if (!$this->isValidColumn($column)) {
            throw new InvalidAggregateColumnException("Unsupported Aggregate Columns: `" . $column . "`.");
        }

        $results = $this->get();
        $sum = 0;
        if (!empty($results)) {
            foreach ($results as $row) {
                if (!empty($row[$column]) && is_numeric($row[$column])) {
                    $sum += $row[$column];
                }
            }
        }

        return $sum;
    }


    /**
     * Calculate the average of a numeric field in the array.
     *
     * @param string $column The key of the numeric field for which to calculate the average.
     * @return int|float The average value of the specified field.
     * @throws ColumnNotFoundException If the provided key is invalid.
     */
    public function avg(string $column): int|float
    {
        if (!$this->isValidColumn($column)) {
            throw new ColumnNotFoundException("Invalid average operation field-key: `" . $column . "`.");
        }

        $results = $this->get();
        $total = count($results);

        if ($total === 0) {
            return 0;
        }

        $sum = 0;
        foreach ($results as $row) {
            if (!empty($row[$column]) && is_numeric($row[$column])) {
                $sum += $row[$column];
            }
        }

        return $total > 0 ? $sum / $total : 0;
    }

    /**
     * Get the minimum value of a specified key from the results.
     *
     * @param string $column The key to find the minimum value.
     * @return array
     * @throws Exception
     */
    public function min(string $column): array
    {
        if (!$this->isValidColumn($column)) {
            throw new InvalidAggregateColumnException("Unsupported Aggregate Columns: `" . $column . "`.");
        }

        $results = $this->get();
        if (empty($results)) {
            return [];
        }

        $values = array_column($results, $column);

        if (!empty($values)) {
            $maxKey = array_search(min($values), $values);
            return $results[$maxKey];
        }

        return [];
    }

    /**
     * Get the maximum value of a specified key from the results.
     *
     * @param string $column The key to find the minimum value.
     * @return array
     * @throws InvalidAggregateColumnException
     * @throws ColumnNotFoundException
     */
    public function max(string $column): array
    {
        if (!$this->isValidColumn($column)) {
            throw new InvalidAggregateColumnException("Unsupported Aggregate Columns: `" . $column . "`.");
        }

        $results = $this->get();
        if (empty($results)) {
            return [];
        }

        $values = array_column($results, $column);

        if (!empty($values)) {
            $maxKey = array_search(max($values), $values);
            return $results[$maxKey];
        }

        return [];
    }


    /** ================ Apply Closures =====================
     * @throws ColumnNotFoundException
     */

    /**
     * Select columns to include in the query.
     *
     * @param array|string $columns Columns to select. Default is ['*'] (all columns).
     * @return static
     * @throws ColumnNotFoundException if an unsupported column is provided.
     */
    public function select(array|string $columns = ['*']): static
    {
        $this->_columns = [];
        $columns = is_array($columns) ? $columns : func_get_args();

        foreach ($columns as $column) {
            if ($column == '*') {
                $this->_columns = ['*'];
                break;
            } else if (!$this->isValidColumn($column)) {
                throw new ColumnNotFoundException("Unsupported column for SELECT : `" . $column . "`.");
            } else {
                $this->_columns[] = $column;
            }
        }

        $this->_columns = array_unique($this->_columns);
        return $this;
    }


    /**
     * Perform a join operation with another array.
     *
     * @param array $array The array to join.
     * @param string $arrayName The name to assign to the joined array.
     * @param string $parentKey The key in the current array to join on.
     * @param string $foreignKey The key in the joined array to join on.
     * @return $this Returns the instance of the class to allow method chaining.
     */
    public function join(array $array, string $arrayName, string $parentKey, string $foreignKey): static
    {
        $this->_join[] = [
            'array' => $array,
            'arrayName' => $arrayName,
            'parentKey' => $parentKey,
            'foreignKey' => $foreignKey
        ];

        return $this;
    }

    /**
     * Add a WHERE clause to the query.
     *
     * @param string $column The column/key to apply the condition on.
     * @param string $operator The comparison operator.
     * @param mixed $value The value to compare against.
     * @return static Returns the instance of the class to allow method chaining.
     * @throws InvalidWhereOperatorException If the provided operator is invalid.
     * @throws ColumnNotFoundException
     *
     */
    public function where(string $column, string $operator, mixed $value = null): static
    {
        $this->_where[] = $this->prepareWhereOperatorAndValue($column, $operator, $value);

        return $this;
    }

    /**
     * Add a WHERE clause to the query.
     *
     * @param string $column The column/key to apply the condition on.
     * @param string $operator The comparison operator.
     * @param mixed $value The value to compare against.
     * @return static Returns the instance of the class to allow method chaining.
     * @throws InvalidWhereOperatorException If the provided operator is invalid.
     * @throws ColumnNotFoundException
     *
     */
    public function orWhere(string $column, string $operator, mixed $value = null): static
    {
        $this->_or_where[] = $this->prepareWhereOperatorAndValue($column, $operator, $value);
        return $this;
    }

    /**
     * Add a WHERE clause to the query.
     *
     * @param string $column The column/key to apply the condition on.
     * @param string $operator The comparison operator.
     * @param string|null $value
     * @return static Returns the instance of the class to allow method chaining.
     * @throws InvalidWhereOperatorException If the provided operator is invalid.
     * @throws ColumnNotFoundException
     * @throws InvalidDateStringException
     *
     */
    public function whereDate(string $column, string $operator, string|null $value = null): static
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        if (!isDateString($value)) {
            throw new InvalidDateStringException();
        }

        $value = date('Y-m-d', strtotime($value));
        return $this->where($column, $operator . "_DATE", $value);
    }

    /**
     * Add a WHERE clause to the query.
     *
     * @param string $column The column/key to apply the condition on.
     * @param mixed $value
     * @param string $operator The comparison operator. default=both (contains)
     * @return static Returns the instance of the class to allow method chaining.
     * @throws InvalidWhereOperatorException If the provided operator is invalid.
     * @throws ColumnNotFoundException
     *
     */
    public function whereLike(string $column, mixed $value, string $operator = 'both'): static
    {
        $operator = 'like_' . $operator;
        return $this->where($column, $operator, $value);
    }

    /**
     * Add a WHERE clause to the query.
     *
     * @param string $column The column/key to apply the condition on.
     * @param array $values
     * @return static Returns the instance of the class to allow method chaining.
     * @throws ColumnNotFoundException
     * @throws InvalidWhereOperatorException
     *
     */
    public function whereIn(string $column, array $values): static
    {
        return $this->where($column, 'IN_ARRAY', $values);
    }

    /**
     * Add a WHERE clause to the query.
     *
     * @param string $column The column/key to apply the condition on.
     * @param array $values
     * @return static Returns the instance of the class to allow method chaining.
     * @throws ColumnNotFoundException
     * @throws InvalidWhereOperatorException
     *
     */
    public function whereNotIn(string $column, array $values): static
    {
        return $this->where($column, 'NOT_IN_ARRAY', $values);
    }

    /**
     * Apply sorting to the query result.
     *
     * @param string $column The column/key to sort by.
     * @param string $operator The sorting direction (default: 'ASC').
     * @return $this Returns the instance of the class to allow method chaining.
     * @throws InvalidSortingKeyException If the provided sorting key is invalid.
     * @throws InvalidSortingOperatorException If the provided sorting operator is invalid.
     * @throws MultipleSortingOperationException
     */
    public function orderBy(string $column, string $operator = 'DESC'): static
    {
        if (!$this->isValidColumn($column)) {
            throw new InvalidSortingKeyException("Invalid ordering/sorting operation key: `" . $column . "`.");
        }

        if (!SortingClosure::isValidOperator($operator)) {
            throw new InvalidSortingOperatorException("Invalid ordering/sorting operator: `" . $operator . "`.");
        }

        if (!empty($this->_order)) {
            throw new MultipleSortingOperationException();
        }

        $this->_order = [
            'key' => $column,
            'order' => strtoupper($operator)
        ];

        return $this;
    }

    /**
     * Limit the elements of the array.
     *
     * @param int $offset The starting index of the limit.
     * @param int $length The number of elements to limit.
     * @return static Returns the modified instance of the class.
     */
    public function limit(int $length, int $offset = 0): static
    {
        $this->_limit = [
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }



    /* ================= private Methods =================== */

    /**
     * Checks if the provided operator is valid.
     *
     * @param string $column
     * @return bool True if the operator is valid, false otherwise.
     */
    private function isValidColumn(string $column): bool
    {
        if (empty($this->_fields) || !in_array($column, $this->_fields)) {
            return false;
        }

        return true;
    }

    /**
     * Prepare the where operator and value.
     *
     * @param string $column The key (field name) for the WHERE operation.
     * @param string $operator The operator for the WHERE operation.
     * @param mixed|null $value The value for comparison. Defaults to null.
     * @return array An array containing the prepared key, value, and operator.
     * @throws ColumnNotFoundException If the key is unsupported.
     * @throws InvalidWhereOperatorException If the operator is unsupported.
     */
    private function prepareWhereOperatorAndValue(string $column, string $operator, mixed $value = null): array
    {
        // If the value is not provided, assume that the operator itself is the value and set default operator to '='
        if ($value === null) {
            $value = $operator; // The operator becomes the value
            $operator = "=";    // Default operator is '='
        }

        if (!$this->isValidColumn($column)) {
            throw new ColumnNotFoundException("Unsupported key for WHERE operation: `" . $column . "`.");
        }

        if (!WhereClosure::isValidOperator($operator)) {
            throw new InvalidWhereOperatorException("Unsupported operator: " . $operator);
        }

        return [
            'key' => $column,
            'value' => $value,
            'operator' => $operator
        ];
    }

}

