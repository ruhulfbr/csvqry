# Query on CSV

**Query on CSV** allows you to perform queries on CSV.

## Use Cases

This package is suitable for you if you need to perform some queries on:

* Perform SELECT query
* Perform where query (where, orWhere, whereIn, whereDate e.t.c)
* Perform Sorting
* Perform Limit, Offset
* Perform Aggregate Query (count, sum, avg, max, min)

## Basic Usage

To instantiate the CSVQ do the following:

```php
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

```

## Querying, sorting and get results

You can perform queries on your csv:

```php

$result = CSVQ::from("example.csv")
        ->select('id', 'name')
        //->select(['id', 'name'])
        ->where('id', 2)
        //->where('id', '>' ,2)
        ->orWhere('id', 3)
        //->orWhere('id', '>=', 3)
        ->whereDate('dob', '2010-10-10')
        //->whereDate('dob', '>=','2010-10-10')
        ->whereLike('name', 'ruhul')
        //->whereLike('name', 'ruhul', 'start')
        //->whereLike('name', 'ruhul', 'end')
        ->whereIn('age', [22,23,25,26])
        ->whereNotIn('age', [11,12,13])
        
        ->orderBy('id')
        //->orderBy('id', 'desc')
        //->orderBy('id', 'asc')
        //->latest('id')  // Default Id
        //->oldest('id')  // Default Id
        ->get();

```

### More Example

```php

// To Get All Result
$result = CSVQ::from("example.csv")->all();

// To Get All Sorted Result
$result = CSVQ::from("example.csv")->orderBy('id', 'desc')->all();

// To Get Specific Row
$result = CSVQ::from("example.csv")->where('id', 1)->row();

// To Get First Result
$result = CSVQ::from("example.csv")->where('id', 1)->first();

// To Get Last Result
$result = CSVQ::from("example.csv")->where('id', 1)->last();

// To Get nth row
$result = CSVQ::from("example.csv")->getNth(2); // [0-n]

// Check Is row exist
$result = CSVQ::from("example.csv")->where('id', 1)->hasData(); // boolean
$result = CSVQ::from("example.csv")->where('id', 1)->doesExist(); // boolean

// To Get All Sorted Result
$result = CSVQ::from("example.csv")->orderBy('id', 'desc')->all();

```

### Available where operators

* `=` (default operator, can be omitted)
* `>`
* `<`
* `<=`
* `>=`
* `!=`

### Available sorting operators

* `ASC`
* `DESC` (default operator, can be omitted)
* `asc`
* `desc`

## Limit and Offset

You can add criteria and specify limit and offset for your query results:

```php

$result = CSVQ::from("example.csv")
        ->select('*')
        ->orderBy('id')
        ->limit(10)
        //->limit(10, 2)    
        ->get();

```

## Aggregator Query

You can add criteria and specify limit and offset for your query results:

```php

// To Get Count
$result = CSVQ::from("example.csv")->count();

// To Get Sum
$result = CSVQ::from("example.csv")->sum('age');

// To Get Average
$result = CSVQ::from("example.csv")->avg('age');

// To Get row with minimum column value
$result = CSVQ::from("example.csv")->min('age');

// To Get row with maximum column value
$result = CSVQ::from("example.csv")->max('age');

```

## Support

If you found an issue or had an idea please refer [to this section](https://github.com/ruhulfbr/csvqry/issues).

## Authors

* **Md Ruhul Amin** - [Github](https://github.com/ruhulfbr)
