<?php
namespace Maishapay\Customer\Action;

use Maishapay\Customer\Customer;
use Maishapay\Customer\CustomerMapper;
use Maishapay\Customer\CustomerTransformer;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class CreateCustomerAction
{
    protected $logger;
    protected $renderer;
    protected $customerMapper;

    public function __construct(Logger $logger, HalRenderer $renderer, CustomerMapper $customerMapper)
    {
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->customerMapper = $customerMapper;
    }

    public function __invoke($request, $response)
    {
        $data = $request->getParsedBody();
        $this->logger->info("Creating a new customer", ['data' => $data]);

        $customer = new Customer($data);
        $this->customerMapper->insert($customer);

        $transformer = new CustomerTransformer();
        $hal = $transformer->transform($customer);

        $response = $this->renderer->render($request, $response, $hal);
        return $response->withStatus(201);
    }
}
