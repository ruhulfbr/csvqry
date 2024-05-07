<?php

/**
 * src/Exceptions/FileTypeNotAllowedException.php
 *
 * @package ruhulfbr/csvqry
 * @author Md Ruhul Amin (ruhul11bd@gmail.com)
 */

namespace Ruhul\CSVQuery\Exceptions;

class FileTypeNotAllowedException extends \Exception
{
    public function __construct(string $message = "Only CSV files are allowed")
    {
        parent::__construct($message);
    }
}
