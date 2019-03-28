<?php
return [
    'displayErrorDetails' => true, // set to false in production
    'addContentLengthHeader' => false,

    // OAuth 2 configuration
    'oauth2' => [
        'use_jwt_bearer_tokens' => true,
    ],

    // Database adapter
    'db' => [
        'dsn' => 'mysql:host=localhost;dbname=api_db',
        'user' => 'root',
        'pass' => '',
    ],

    // Monolog
    'logger' => [
        'name' => 'slim-bookshelf-api',
        // uncomment 'path' setting to log to file rather than the error log
         'path' => __DIR__ . '/../var/app.log',
    ],
];
