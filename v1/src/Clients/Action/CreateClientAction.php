<?php
namespace Maishapay\Client\Action;

use Maishapay\Client\Client;
use Maishapay\Client\ClientMapper;
use Maishapay\Client\ClientTransformer;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class CreateClientAction
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
        $data = $request->getParsedBody();
        $this->logger->info("Creating a new client", ['data' => $data]);

        $client = new Client($data);
        $this->clientMapper->insert($client);

        $transformer = new ClientTransformer();
        $hal = $transformer->transform($client);

        $response = $this->renderer->render($request, $response, $hal);
        return $response->withStatus(201);
    }
}
