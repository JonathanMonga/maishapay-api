<?php
// Routes

$app->get('/', Maishapay\App\Action\HomeAction::class); //Home route
$app->get('/ping', Maishapay\App\Action\PingAction::class); //Ping route

// Routes that need a valid user token
$app->group('', function () use ($app) {
    // basics methods
    $app->get('/signin', Maishapay\Customer\Action\GetAllCustomersAction::class); //Get all customers
    $app->get('/signup', Maishapay\Customer\Action\GetAllCustomersAction::class); //Get all customers

    // All methods about customers
    $app->get('/customers', Maishapay\Customer\Action\GetAllCustomersAction::class); //Get all customers

    /*
    $app->get('/customers', Maishapay\Customer\Action\GetAllCustomersAction::class); //Get all customers
    $app->post('/customers', Maishapay\Customer\Action\CreateCustomerAction::class); //Create new customer
    $app->get('/customers/{customer_uuid}', Maishapay\Customer\Action\GetCustomerByUUIDAction::class); //Get a customer by his uuid
    $app->put('/customers/{customer_uuid}', Maishapay\Customer\Action\EditCustomerAction::class); //Update a customer
    $app->delete('/customers/{customer_uuid}', Maishapay\Customer\Action\DeleteCustomerAction::class); //Delete a customer

    // All methods about accounts
    $app->get('/accounts', Maishapay\Customer\Action\GetAllCustomersAction::class); //Get all customers
    $app->get('/accounts', Maishapay\Customer\Action\GetAllCustomersAction::class); //Get all customers
    $app->post('/accounts', Maishapay\Customer\Action\CreateCustomerAction::class); //Create new customer
    $app->get('/accounts/{customer_uuid}', Maishapay\Customer\Action\GetCustomerByUUIDAction::class); //Get a customer by his uuid
    $app->put('/accounts/{customer_uuid}', Maishapay\Customer\Action\EditCustomerAction::class); //Update a customer
    $app->delete('/accounts/{customer_uuid}', Maishapay\Customer\Action\DeleteCustomerAction::class); //Delete a customer

    // Basic developpers methods
    $app->get('/dev/signin', Maishapay\Customer\Action\GetAllCustomersAction::class); //Get all customers
    $app->get('/dev/signup', Maishapay\Customer\Action\GetAllCustomersAction::class); //Get all customers

    //Developper projects methods
    $app->get('/projects', Maishapay\Customer\Action\GetAllCustomersAction::class); //Get all customers

    // Developpers live mode methods
    $app->get('/live/customers', Maishapay\Customer\Action\GetAllCustomersAction::class); //Get all customers

    // Developpers sandbox mode methods
    $app->get('/sandbox/customers', Maishapay\Customer\Action\GetAllCustomersAction::class); //Get all customers
    */

    $app->post('/authorise', Maishapay\Auth\Action\AuthoriseAction::class); //Authorize all clients
})->add(Maishapay\Auth\GuardMiddleware::class);

// Auth routes
$app->post('/token', Maishapay\Auth\Action\TokenAction::class); //Get access token