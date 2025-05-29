<?php 
namespace App\Session;

use MongoDB\Client;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MongoDbSessionHandler;

class MongoSessionHandler extends MongoDbSessionHandler
{
    public function __construct()
    {
        $mongoClient = new Client(env('DB_DSN', 'mongodb://127.0.0.1:27017'));
        $database = env('DB_DATABASE', 'your_database');

        parent::__construct($mongoClient, [
            'database'   => $database,
            'collection' => 'sessions',
            'id_field'   => '_id',
            'data_field' => 'payload',
            'time_field' => 'last_activity',
        ]);
    }
}
