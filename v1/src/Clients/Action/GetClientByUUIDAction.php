<?php
namespace Maishapay\Clients\Action;

use Maishapay\Clients\ClientMapper;
use Maishapay\Clients\ClientTransformer;
use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class GetClientByUUIDAction
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
        $client_uuid = $request->getAttribute('id');
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
