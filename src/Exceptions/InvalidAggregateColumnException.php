<?php

/**
 * src/Exceptions/InvalidAggregateColumnException.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com) <https://github.com/ruhulfbr>
 */

namespace Ruhul\CSVQuery\Exceptions;

class InvalidAggregateColumnException extends \Exception
{
    public function __construct(string $message = "Unsupported Aggregate Column")
    {
        parent::__construct($message);
    }
}