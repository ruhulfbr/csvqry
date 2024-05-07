<?php

/**
 * src/Exceptions/InvalidFilePathException.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com)
 */

namespace Ruhul\CSVQuery\Exceptions;

class InvalidFilePathException extends \Exception
{
    public function __construct(string $message = "Invalid File Path")
    {
        parent::__construct($message);
    }
}
