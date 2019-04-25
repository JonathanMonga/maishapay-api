<?php
namespace Maishapay\Client\Action;

use Maishapay\Client\ClientMapper;
use Maishapay\Client\ClientTransformer;
use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class GetClientByUUIDAction
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
        $this->logger->info("Listing a single client", ['client_uuid' => $client_uuid]);

        $client = $this->clientMapper->loadById($client_uuid);

        if (!$client) {
            $problem = new ApiProblem(
                'Could not find client',
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                404
            );
            throw new ProblemException($problem);
        }

        $transformer = new ClientTransformer();
        $hal = $transformer->transform($client);

        return $this->renderer->render($request, $response, $hal);
    }
}
