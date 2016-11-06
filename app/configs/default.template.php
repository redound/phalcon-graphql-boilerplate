<?php

/**
 * Read more on Config Files
 * @link http://phalcon-rest.redound.org/config_files.html
 */

return [

    'application' => [
        'title' => 'Phalcon GraphQL Boilerplate',
        'description' => '',
        'baseUri' => '/',
        'viewsDir' => __DIR__ . '/../views/',
    ],

    'authentication' => [
        'secret' => 'this_should_be_changed',
        'expirationTime' => 86400 * 7, // One week till token expires
    ]
];
