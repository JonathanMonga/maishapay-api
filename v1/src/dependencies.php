<?php
// DIC configuration

// Register AuthServer services
use Maishapay\App\Action\HomeAction;
use Maishapay\App\Action\PingAction;
use Maishapay\Clients\ClientMapper;
use Maishapay\Customers\CustomerMapper;
use Maishapay\Error\Handler\NotAllowed;
use Maishapay\Error\Handler\NotFound;
use Maishapay\Error\Handler\Error;
use Maishapay\Users\User;
use Maishapay\Users\UserMapper;

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
    return new NotFound();
};
$container['notAllowedHandler'] = function () {
    return new NotAllowed();
};
$container['errorHandler'] = function () {
    return new Error();
};
$container['phpErrorHandler'] = function () {
    return new Error();
};

// Mappers
$container[CustomerMapper::class] = function ($c) {
    return new CustomerMapper($c->get('logger'), $c->get('db'));
};

$container[ClientMapper::class] = function ($c) {
    return new ClientMapper($c->get('logger'), $c->get('db'));
};

$container[UserMapper::class] = function ($c) {
    return new UserMapper($c->get('logger'), $c->get('db'));
};

// Actions
$container[HomeAction::class] = function ($c) {
    $logger = $c->get('logger');
    $renderer = $c->get('renderer');
    return new HomeAction($logger, $renderer);
};

$container[PingAction::class] = function ($c) {
    $logger = $c->get('logger');
    return new PingAction($logger);
};

$customerActionFactory = function ($actionClass) {
    return function ($c) use ($actionClass) {
        $logger = $c->get('logger');
        $renderer = $c->get('renderer');
        $mapper = $c->get(CustomerMapper::class);
        return new $actionClass($logger, $renderer, $mapper);
    };
};

$clientActionFactory = function ($actionClass) {
    return function ($c) use ($actionClass) {
        $logger = $c->get('logger');
        $renderer = $c->get('renderer');
        $customerMapper = $c->get(CustomerMapper::class);
        $clientMapper = $c->get(ClientMapper::class);
        $userMapper = $c->get(UserMapper::class);
        return new $actionClass($logger, $renderer, $customerMapper, $clientMapper, $userMapper);
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