<?php
// Routes

use Maishapay\Auth\Action\{
    AdminAction, AuthoriseAction, TokenAction
};

use Maishapay\Auth\GuardMiddleware;

use Maishapay\Clients\Action\{
    CreateClientAction, DeleteClientAction, EditClientAction, GetAllClientsAction, GetClientByUUIDAction
};
use Maishapay\Customers\Action\{
    CreateCustomerAction, DeleteCustomerAction, EditCustomerAction, GetAllCustomersAction, GetCustomerByUUIDAction
};

$app->get('/', Maishapay\App\Action\HomeAction::class); //Home route
$app->get('/ping', Maishapay\App\Action\PingAction::class); //Ping route

// Routes that need a valid user token
$app->group('', function () use ($app) {
    // basics methods
    $app->post('/signin', CreateCustomerAction::class); //Get all customers
    $app->post('/signup', CreateCustomerAction::class); //Get all customers

    // Basic developpers methods
    $app->post('/customers/create', CreateCustomerAction::class); //Create new client
    $app->delete('/customers/delete', DeleteCustomerAction::class); //Delete a client
    $app->get('/customers/all', GetAllCustomersAction::class); //Get all clients
    $app->get('/customers/{id}', GetCustomerByUUIDAction::class); //Get a client by uuid
    $app->put('/customers/{id}', EditCustomerAction::class); //Update a client by uuid

    $app->delete('/developper/clients/delete', DeleteClientAction::class); //Delete a client
    $app->get('/developper/clients/all', GetAllClientsAction::class); //Get all clients
    $app->get('/developper/clients/{id}', GetClientByUUIDAction::class); //Get a client by uuid
    $app->put('/developper/clients/{id}', EditClientAction::class); //Update a client by uuid

    // Developper only methods
    //$app->get('/live/customers', Maishapay\Customers\Action\GetAllCustomersAction::class); //Get all customers

    // Developpers sandbox mode methods
    //$app->get('/sandbox/customers', Maishapay\Customers\Action\GetAllCustomersAction::class); //Get all customers

    $app->post('/authorise', AuthoriseAction::class); //Authorize all clients
})->add(GuardMiddleware::class);

// Auth routes
$app->post('/token', TokenAction::class); //Get access token

//Create new client
$app->post('/developper/clients/create', CreateClientAction::class);