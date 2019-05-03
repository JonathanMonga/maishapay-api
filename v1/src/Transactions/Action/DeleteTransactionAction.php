<?php
namespace Maishapay\Transactions\Action;

use Maishapay\Clients\ClientMapper;
use Maishapay\Customers\CustomerMapper;
use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Maishapay\Users\UserMapper;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class DeleteClientAction
{
    protected $logger;
    protected $renderer;
    protected $clientMapper;
    protected $customerMapper;
    protected $userMapper;

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
        $client_uuid = $request->getAttribute('client_uuid');
        $this->logger->info("Deleting an client", ['client_uuid' => $client_uuid]);

        $client = $this->clientMapper->loadById($client_uuid);
        $customer = $this->customerMapper->loadById($client_uuid);
        $user = $this->userMapper->loadById($client_uuid);

        if (!$client && !$customer && !$user) {
            $problem = new ApiProblem(
                'Could not find client',
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                404
            );

            throw new ProblemException($problem);
        }

        $this->clientMapper->delete($client_uuid);
        $this->customerMapper->delete($client_uuid);
        $this->userMapper->delete($client_uuid);

        return $response->withStatus(204);
    }
}