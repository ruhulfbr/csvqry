<?php

/**
 * src/Exceptions/MultipleSortingOperationException.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com)
 */

namespace Ruhul\CSVQuery\Exceptions;

class MultipleSortingOperationException extends \Exception
{
    public function __construct(string $message = "Multiple ordering/sorting operations are not allowed.")
    {
        parent::__construct($message);
    }
}