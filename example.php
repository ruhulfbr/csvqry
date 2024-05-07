<?php

require_once 'vendor/autoload.php';

use Ruhul\CSVQuery\CSVQ;

try {
    $result = CSVQ::from("example.csv")
        ->select('id', 'name')
        ->get();

} catch (\Exception $e) {
    $result = $e->getMessage();
}

pr($result);
echo PHP_EOL;