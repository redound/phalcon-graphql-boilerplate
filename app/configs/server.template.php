<?php

return [

    'debug' => true,
    'cachingEnabled' => false,

    'hostName' => 'http://phalcon-graphql-boilerplate.redound.dev',
    'clientHostName' => 'http://phalcon-graphql-app.redound.dev',
    'database' => [

        // Change to your own configuration
        'adapter' => 'Mysql',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => 'root',
        'dbname' => 'phalcon_graphql_boilerplate',
    ],
    'cors' => [
        'allowedOrigins' => ['*']
    ],
    'redis' => [
        "host" => "localhost",
        "port" => 6379,
        "persistent" => false
    ]
];
