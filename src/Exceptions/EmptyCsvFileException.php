<?php

/**
 * src/Exceptions/EmptyCsvFileException.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com)
 */

namespace Ruhul\CSVQuery\Exceptions;

class EmptyCsvFileException extends \Exception
{
    public function __construct(string $message = "The CSV file is empty.")
    {
        parent::__construct($message);
    }
}
