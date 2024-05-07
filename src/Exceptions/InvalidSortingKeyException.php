<?php

/**
 * src/Exceptions/InvalidSortingKeyException.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com)
 */

namespace Ruhul\CSVQuery\Exceptions;

class InvalidSortingKeyException extends \Exception
{
    public function __construct(string $message = "Invalid sorting/ordering key exception")
    {
        parent::__construct($message);
    }
}