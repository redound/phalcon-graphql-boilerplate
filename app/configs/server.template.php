<?php

/**
 * Read more on Config Files
 * @link http://phalcon-rest.redound.org/config_files.html
 */

return [

    'debug' => true,
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
    ]
];
