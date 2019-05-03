<?php
namespace Maishapay\Transactions\Action;

use Maishapay\Clients\ClientMapper;
use Maishapay\Clients\ClientTransformer;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class GetAllClientsAction
{
    protected $logger;
    protected $renderer;
    protected $clientMapper;

    public function __construct(Logger $logger,
                                HalRenderer $renderer,
                                CustomerMapper $customerMapper,
                                ClientMapper $clientMapper,
                                UserMapper $userMapper)
    {
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->customerMapper = $customerMapper;
        $this->clientMapper = $clientMapper;
        $this->userMapper = $userMapper;
    }

    public function __invoke($request, $response)
    {
        $this->logger->info("Listing all clients");

        $checklists = $this->clientMapper->fetchAll();

        $transformer = new ClientTransformer();
        $hal = $transformer->transformCollection($checklists);

        return $this->renderer->render($request, $response, $hal);
    }
}
