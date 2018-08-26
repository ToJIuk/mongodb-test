<?php

use DB\DB;

require_once 'vendor/autoload.php';
require_once 'forms/form.php';

$mongo = new DB();
$mongo->initUsers();
$mongo->initIndex();

$search = $mongo->instance->find(['$text' => ['$search' => 'some']]);

foreach ($search as $item) {
    echo '<pre>';
    print_r($item);
   echo $item['name'] . ' - ' . $item['about'];
}

$aggreg = $mongo->instance->aggregate([
    ['$group' => ['_id' => '$name', 'count' => ['$sum' => 1]]],
    ['$sort' => ['count' => -1]],
    ['$limit' => 7],
]);

foreach ($aggreg as $item) {
    echo $item['_id'] . ' has ' . $item['count'] . '<br>';
}

$users = $mongo->instance->find([], [
    'projection' => [
        'name' => 1,
        "age" => 1,
    ],
    'limit' => 7,
    'sort' => ['age' => -1]
]);

require_once 'forms/result.php';