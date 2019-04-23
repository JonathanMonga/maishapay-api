<?php
namespace Maishapay\Customer\Action;

use Maishapay\Customer\CustomerMapper;
use Maishapay\Customer\CustomerTransformer;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class GetAllCustomersAction
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
        $this->logger->info("Listing all customers");

        $checklists = $this->customerMapper->fetchAll();

        $transformer = new CustomerTransformer();
        $hal = $transformer->transformCollection($checklists);

        return $this->renderer->render($request, $response, $hal);
    }
}
