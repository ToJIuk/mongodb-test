<?php

namespace DB;

use function MongoDB\BSON\fromJSON;
use function MongoDB\BSON\toPHP;
use MongoDB\Driver\Manager;
use MongoDB\Driver\BulkWrite;

class DB
{
    private $connection = 'mongodb://localhost:27017';
    private $db = 'mydb';
    public $instance;

    public function __construct()
    {
        $mongo = new \MongoDB\Client($this->connection);
        $this->instance = $mongo->selectCollection($this->db, 'users');
    }

    public function initUsers()
    {
        $this->instance->drop();
        $data = file(__DIR__ . DIRECTORY_SEPARATOR .'../dump-db/users', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $bulk = new BulkWrite;
        foreach ($data as $item) {
            $bson = fromJSON($item);
            $row = toPHP($bson);
            $bulk->insert($row);
        }
        $manager = new Manager($this->connection);
        $manager->executeBulkWrite($this->db . '.users', $bulk);
    }

    public function initIndex()
    {
        $this->instance->createIndex(['about' => 'text']);
    }
}