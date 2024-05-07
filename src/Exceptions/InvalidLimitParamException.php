<?php

/**
 * src/Exceptions/InvalidLimitParamException.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com)
 */

namespace Ruhul\CSVQuery\Exceptions;

class InvalidLimitParamException extends \Exception
{
    public function __construct(string $message = "Invalid array limit params")
    {
        parent::__construct($message);
    }
}
