<?php
// Routes

use Maishapay\Auth\Action\{
    AuthoriseAction,
    TokenAction
};

use Maishapay\Auth\GuardMiddleware;

use Maishapay\Clients\Action\CreateClientAction;
use Maishapay\Customers\Action\CreateCustomerAction;
use Maishapay\Customers\Action\GetAllCustomersAction;

$app->get('/', Maishapay\App\Action\HomeAction::class); //Home route
$app->get('/ping', Maishapay\App\Action\PingAction::class); //Ping route

// Routes that need a valid user token
$app->group('', function () use ($app) {
    // basics methods
    $app->post('/signin', CreateCustomerAction::class); //Get all customers
    $app->post('/signup', CreateCustomerAction::class); //Get all customers

    // All methods about customers
    $app->get('/customers', GetAllCustomersAction::class); //Get all customers

    /*
    $app->get('/customers', Maishapay\Customers\Action\GetAllCustomersAction::class); //Get all customers
    $app->post('/customers', Maishapay\Customers\Action\CreateCustomerAction::class); //Create new customer
    $app->get('/customers/{customer_uuid}', Maishapay\Customers\Action\GetCustomerByUUIDAction::class); //Get a customer by his uuid
    $app->put('/customers/{customer_uuid}', Maishapay\Customers\Action\EditCustomerAction::class); //Update a customer
    $app->delete('/customers/{customer_uuid}', Maishapay\Customers\Action\DeleteCustomerAction::class); //Delete a customer

    // All methods about accounts
    $app->get('/accounts', Maishapay\Customers\Action\GetAllCustomersAction::class); //Get all customers
    $app->get('/accounts', Maishapay\Customers\Action\GetAllCustomersAction::class); //Get all customers
    $app->post('/accounts', Maishapay\Customers\Action\CreateCustomerAction::class); //Create new customer
    $app->get('/accounts/{customer_uuid}', Maishapay\Customers\Action\GetCustomerByUUIDAction::class); //Get a customer by his uuid
    $app->put('/accounts/{customer_uuid}', Maishapay\Customers\Action\EditCustomerAction::class); //Update a customer
    $app->delete('/accounts/{customer_uuid}', Maishapay\Customers\Action\DeleteCustomerAction::class); //Delete a customer

    //Developper projects methods
    $app->get('/projects', Maishapay\Customers\Action\GetAllCustomersAction::class); //Get all customers

    // Developpers live mode methods
    $app->get('/live/customers', Maishapay\Customers\Action\GetAllCustomersAction::class); //Get all customers

    // Developpers sandbox mode methods
    $app->get('/sandbox/customers', Maishapay\Customers\Action\GetAllCustomersAction::class); //Get all customers
    */

    // Basic developpers methods
    $app->post('/developper/signup', CreateClientAction::class); //Create new developper account

    $app->post('/authorise', AuthoriseAction::class); //Authorize all clients
})->add(GuardMiddleware::class);

// Auth routes
$app->post('/token', TokenAction::class); //Get access token