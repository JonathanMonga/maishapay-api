<?php
namespace Maishapay\Customers\Action;

use Maishapay\Customers\CustomerMapper;
use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class DeleteCustomerAction
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
        $customer_uuid = $request->getAttribute('customer_uuid');
        $this->logger->info("Deleting an customer", ['customer_uuid' => $customer_uuid]);

        $customer = $this->customerMapper->loadById($customer_uuid);

        if (!$customer) {
            $problem = new ApiProblem(
                'Could not find customer',
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                404
            );
            throw new ProblemException($problem);
        }

        $this->customerMapper->delete($customer_uuid);
        return $response->withStatus(204);
    }
}
