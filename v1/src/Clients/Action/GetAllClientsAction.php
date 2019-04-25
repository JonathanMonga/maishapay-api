<?php
namespace Maishapay\Client\Action;

use Maishapay\Client\ClientMapper;
use Maishapay\Client\ClientTransformer;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class GetAllClientsAction
{
    protected $logger;
    protected $renderer;
    protected $clientMapper;

    public function __construct(Logger $logger, HalRenderer $renderer, ClientMapper $clientMapper)
    {
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->clientMapper = $clientMapper;
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
