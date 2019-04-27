<?php
namespace Maishapay\Clients\Action;

use Maishapay\Clients\ClientMapper;
use Maishapay\Clients\ClientTransformer;
use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class EditClientAction
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
        $client_uuid = $request->getAttribute('client_uuid');
        $data = $request->getParsedBody();
        $this->logger->info("Updating an client", ['client_uuid' => $client_uuid, 'data' => $data]);

        $client = $this->clientMapper->loadById($client_uuid);

        if (!$client) {
            $problem = new ApiProblem(
                'Could not find client',
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                404
            );
            throw new ProblemException($problem);
        }

        $client->update($data);
        $this->clientMapper->update($client);

        $transformer = new ClientTransformer();
        $hal = $transformer->transform($client);

        $response = $this->renderer->render($request, $response, $hal);
        return $response->withStatus(200);
    }
}
