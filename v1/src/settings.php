<?php
return [
    'displayErrorDetails' => true, // set to false in production
    'addContentLengthHeader' => false,

    // OAuth 2 configuration
    'oauth2' => [
        'use_jwt_bearer_tokens' => false,
    ],

    // Database adapter
    'db' => [
        'dsn' => getenv('DB_DSN') ?: 'mysql:host=localhost;dbname=cp973977_maishapay-api',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: null

        /*'dsn' => getenv('DB_DSN') ?: getenv('TEST_DB_DSN'),
        'user' => getenv('DB_USER') ?: getenv('TEST_DB_USER'),
        'pass' => getenv('DB_PASS') ?: getenv('TEST_DB_PASS'),*/
    ],

    // Monolog
    'logger' => [
        'name' => 'maishapay-api',
        // uncomment 'path' setting to log to file rather than the error log
         'path' => __DIR__ . '/../var/app.log',
    ],
];
