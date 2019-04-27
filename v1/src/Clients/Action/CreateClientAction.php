<?php
namespace Maishapay\Clients\Action;

use Maishapay\Clients\Client;
use Maishapay\Clients\ClientMapper;
use Maishapay\Clients\ClientTransformer;
use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
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

    public function __invoke($request, $response, $insertClient)
    {
        $data = $request->getParsedBody();
        $this->logger->info("Creating a new client", ['data' => $data]);

        if($data){
            $client = new Client($data);

            $transformer = new ClientTransformer();
            $hal = $transformer->transform($this->clientMapper->insert($client));

            $response = $this->renderer->render($request, $response, $hal);
            return $response->withStatus(201);
        } else {
            $problem = new ApiProblem(
                'Body is empty',
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                404
            );

            throw new ProblemException($problem);
        }
    }
}