<?php
namespace Maishapay\Clients\Action;

use Maishapay\Clients\Client;
use Maishapay\Clients\ClientMapper;
use Maishapay\Clients\ClientTransformer;
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

        if($data){
            $client = new Client($data);
            $this->clientMapper->insert($client);
        } else {
            $client = new Client([]);
            $this->clientMapper->insert($client);
        }

        $transformer = new ClientTransformer();
        $hal = $transformer->transform($client);

        $response = $this->renderer->render($request, $response, $hal);
        return $response->withStatus(201);
    }
}
