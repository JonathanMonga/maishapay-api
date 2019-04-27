<?php
namespace Maishapay\Customers\Action;

use Maishapay\Customers\{
    Customer, CustomerMapper, CustomerTransformer
};
use Maishapay\Users\{
    User, UserMapper
};
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class CreateCustomerAction
{
    protected $logger;
    protected $renderer;
    protected $customerMapper;
    protected $userMapper;

    public function __construct(Logger $logger,
                                HalRenderer $renderer,
                                CustomerMapper $customerMapper,
                                UserMapper $userMapper)
    {
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->customerMapper = $customerMapper;
        $this->userMapper = $userMapper;
    }

    public function __invoke($request, $response)
    {
        $data = $request->getParsedBody();
        $this->logger->info("Creating a new customer", ['data' => $data]);

        $customer = new Customer($data);
        $this->customerMapper->insert($customer);

        $user = new User($data);
        $this->customerMapper->insert($user);

        $transformer = new CustomerTransformer();
        $hal = $transformer->transform($customer);

        $response = $this->renderer->render($request, $response, $hal);
        return $response->withStatus(201);
    }
}
