<?php

/**
 * src/Exceptions/InvalidWhereKeyException.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com)
 */

namespace Ruhul\CSVQuery\Exceptions;

class InvalidWhereKeyException extends \Exception
{
    public function __construct(string $message = "Invalid array key exception")
    {
        parent::__construct($message);
    }
}
