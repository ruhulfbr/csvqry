<?php

/**
 * src/Exceptions/InvalidSortingOperatorException.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com)
 */

namespace Ruhul\CSVQuery\Exceptions;

class InvalidSortingOperatorException extends \Exception
{
    public function __construct(string $message = "Invalid sorting operator")
    {
        parent::__construct($message);
    }
}
