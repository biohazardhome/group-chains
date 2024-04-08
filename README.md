```php
<?php

include('vendor/autoload.php');

use Carbon\Carbon;

$item = [
    'date_to' => '2024-02-01 09:11:46',
    'date_from' => '2024-03-06 12:53:25',
    'create_dt' => '2024-07-02 18:42:12',
];

function carbon($data) {
    return (new Carbon($data));
}

groupChains(
    $item['date_to'],
    $item['date_from'],
    $item['create_dt'],
)->wrap(Carbon::class)
// )->wrap('carbon')
// )->wrap(function ($data) { return new Carbon($data); })
// )->wrap(fn ($data) => new Carbon($data))
    ->format(DateTime::RFC3339)
    ->run();

var_dump($item);
```