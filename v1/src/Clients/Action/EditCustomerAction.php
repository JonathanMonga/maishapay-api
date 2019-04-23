<?php
namespace Maishapay\Customer\Action;

use Maishapay\Customer\CustomerMapper;
use Maishapay\Customer\CustomerTransformer;
use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class EditCustomerAction
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
        $data = $request->getParsedBody();
        $this->logger->info("Updating an customer", ['customer_uuid' => $customer_uuid, 'data' => $data]);

        $customer = $this->customerMapper->loadById($customer_uuid);

        if (!$customer) {
            $problem = new ApiProblem(
                'Could not find customer',
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                404
            );
            throw new ProblemException($problem);
        }

        $customer->update($data);
        $this->customerMapper->update($customer);

        $transformer = new CustomerTransformer();
        $hal = $transformer->transform($customer);

        $response = $this->renderer->render($request, $response, $hal);
        return $response->withStatus(200);
    }
}
