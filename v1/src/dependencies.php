<?php
// DIC configuration

// Register AuthServer services
$container->register(new Maishapay\Auth\OAuth2ServerProvider());

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    if (!empty($settings['path'])) {
        $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    } else {
        $logger->pushHandler(new Monolog\Handler\ErrorLogHandler(0, Monolog\Logger::DEBUG, true, true));
    }
    return $logger;
};

// HAL renderer
$container['renderer'] = function ($c) {
    return new RKA\ContentTypeRenderer\HalRenderer();
};

// Database adapter
$container['db'] = function ($c) {
    $db = $c->get('settings')['db'];

    $pdo = new PDO($db['dsn'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
};

// Error handlers
$container['notFoundHandler'] = function () {
    return new Maishapay\Error\Handler\NotFound();
};
$container['notAllowedHandler'] = function () {
    return new Maishapay\Error\Handler\NotAllowed();
};
$container['errorHandler'] = function () {
    return new Maishapay\Error\Handler\Error();
};
$container['phpErrorHandler'] = function () {
    return new Maishapay\Error\Handler\Error();
};

// Mappers
$container[Maishapay\Customers\CustomerMapper::class] = function ($c) {
    return new Maishapay\Customers\CustomerMapper($c->get('logger'), $c->get('db'));
};

$container[Maishapay\Clients\ClientMapper::class] = function ($c) {
    return new Maishapay\Clients\ClientMapper($c->get('logger'), $c->get('db'));
};

// Actions
$container[Maishapay\App\Action\HomeAction::class] = function ($c) {
    $logger = $c->get('logger');
    $renderer = $c->get('renderer');
    return new Maishapay\App\Action\HomeAction($logger, $renderer);
};

$container[Maishapay\App\Action\PingAction::class] = function ($c) {
    $logger = $c->get('logger');
    return new Maishapay\App\Action\PingAction($logger);
};

$customerActionFactory = function ($actionClass) {
    return function ($c) use ($actionClass) {
        $logger = $c->get('logger');
        $renderer = $c->get('renderer');
        $mapper = $c->get(Maishapay\Customers\CustomerMapper::class);
        return new $actionClass($logger, $renderer, $mapper);
    };
};

$clientActionFactory = function ($actionClass) {
    return function ($c) use ($actionClass) {
        $logger = $c->get('logger');
        $renderer = $c->get('renderer');
        $mapper = $c->get(Maishapay\Clients\ClientMapper::class);
        return new $actionClass($logger, $renderer, $mapper);
    };
};

// @codingStandardsIgnoreStart
$container[Maishapay\Customers\Action\GetAllCustomersAction::class] = $customerActionFactory(Maishapay\Customers\Action\GetAllCustomersAction::class);
$container[Maishapay\Customers\Action\GetCustomerByUUIDAction::class] = $customerActionFactory(Maishapay\Customers\Action\GetCustomerByUUIDAction::class);
$container[Maishapay\Customers\Action\CreateCustomerAction::class] = $customerActionFactory(Maishapay\Customers\Action\CreateCustomerAction::class);
$container[Maishapay\Customers\Action\EditCustomerAction::class] = $customerActionFactory(Maishapay\Customers\Action\EditCustomerAction::class);
$container[Maishapay\Customers\Action\DeleteCustomerAction::class] = $customerActionFactory(Maishapay\Customers\Action\DeleteCustomerAction::class);

$container[Maishapay\Clients\Action\GetAllClientsAction::class] = $clientActionFactory(Maishapay\Clients\Action\GetAllClientsAction::class);
$container[Maishapay\Clients\Action\GetClientByUUIDAction::class] = $clientActionFactory(Maishapay\Clients\Action\GetClientByUUIDAction::class);
$container[Maishapay\Clients\Action\CreateClientAction::class] = $clientActionFactory(Maishapay\Clients\Action\CreateClientAction::class);
$container[Maishapay\Clients\Action\EditClientAction::class] = $clientActionFactory(Maishapay\Clients\Action\EditClientAction::class);
$container[Maishapay\Clients\Action\DeleteClientAction::class] = $clientActionFactory(Maishapay\Clients\Action\DeleteClientAction::class);
// @codingStandardsIgnoreEnd