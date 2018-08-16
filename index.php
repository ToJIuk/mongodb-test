<?php

use DB\DB;

require_once 'vendor/autoload.php';

$mongo = new DB();
$mongo->initUsers();

$users = $mongo->instance->find([], [
    'projection' => [
        'name' => 1,
        "age" => 1,
    ],
    'limit' => 3
]);

foreach ($users as $item) {
    echo 'Name: ' . $item['name'] . ', age: ' . $item['age'] . '<br>';
}
