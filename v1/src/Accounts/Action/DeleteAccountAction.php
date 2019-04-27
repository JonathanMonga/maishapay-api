<?php
namespace Maishapay\Customers\Action;

use Maishapay\Customers\CustomerMapper;
use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Maishapay\Users\UserMapper;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class DeleteCustomerAction
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
        $customer_uuid = $request->getAttribute('customer_uuid');
        $this->logger->info("Deleting an customer", ['customer_uuid' => $customer_uuid]);

        $customer = $this->customerMapper->loadById($customer_uuid);
        $user = $this->userMapper->loadById($customer_uuid);

        if (!$customer && !$user) {
            $problem = new ApiProblem(
                'Could not find customer',
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                404
            );
            throw new ProblemException($problem);
        }

        $this->customerMapper->delete($customer_uuid);
        $this->userMapper->delete($customer_uuid);

        return $response->withStatus(204);
    }
}
