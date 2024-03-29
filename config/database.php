<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mongodb'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [
        'pgsql' => [
            'driver'         => 'pgsql',
            'host'           => env('DB_HOST', '127.0.0.1'),
            'port'           => env('DB_PORT', '5432'),
            'database'       => env('DB_DATABASE', 'forge'),
            'username'       => env('DB_USERNAME', 'forge'),
            'password'       => env('DB_PASSWORD', ''),
            'charset'        => 'utf8',
            'prefix'         => '',
            'prefix_indexes' => TRUE,
            'schema'         => 'public',
            'sslmode'        => 'prefer',
        ],

        'mongodb' => [
            'driver'   => 'mongodb',
            'host'     => env('DB_HOST', 'localhost'),
            'port'     => env('DB_PORT', 27017),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'options'  => [
                'database' => 'admin',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [
        'client'  => 'predis',
        'cluster' => env('REDIS_CLUSTER', FALSE),

        'clusters' => [
            'default' => [
                [
                    'scheme'             => env('REDIS_SCHEME', 'tcp'),
                    'host'               => env('REDIS_HOST', '127.0.0.1'),
                    'password'           => env('REDIS_PASSWORD', NULL),
                    'port'               => env('REDIS_PORT', 6379),
                    'database'           => env('REDIS_DATABASE', 0),
                    'read_write_timeout' => 60,
                ],
            ],
            'options' => [
                'cluster' => 'redis',
            ],
        ],

        'cache' => [
            'host'               => env('REDIS_HOST', '127.0.0.1'),
            'password'           => env('REDIS_PASSWORD', NULL),
            'port'               => env('REDIS_PORT', 6379),
            'database'           => env('REDIS_CACHE_DB', 1),
            'read_write_timeout' => 60,
        ],

        'options' => [
            'parameters' => [
                'password' => env('REDIS_PASSWORD', NULL),
                'scheme'   => env('REDIS_SCHEME', 'tcp'),
            ],
            'ssl'        => ['verify_peer' => FALSE],
        ],
    ],

];
