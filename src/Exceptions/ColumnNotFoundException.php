<?php

/**
 * src/Exceptions/ColumnNotFoundException.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com)
 */

namespace Ruhul\CSVQuery\Exceptions;

class ColumnNotFoundException extends \Exception
{
    public function __construct(string $message = "Column Not found")
    {
        parent::__construct($message);
    }
}
