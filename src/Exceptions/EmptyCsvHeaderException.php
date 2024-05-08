<?php

/**
 * src/Exceptions/EmptyCsvHeaderException.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com)
 */

namespace Ruhul\CSVQuery\Exceptions;

class EmptyCsvHeaderException extends \Exception
{
    public function __construct(string $message = "The CSV file header is empty.")
    {
        parent::__construct($message);
    }
}