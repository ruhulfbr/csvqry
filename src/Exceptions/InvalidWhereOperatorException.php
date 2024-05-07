<?php

/**
 * src/Exceptions/InvalidWhereOperatorException.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com)
 */

namespace Ruhul\CSVQuery\Exceptions;

class InvalidWhereOperatorException extends \Exception
{
    public function __construct(string $message = "Invalid where operator")
    {
        parent::__construct($message);
    }
}
