<?php
namespace Maishapay\Transactions\Action;

use Maishapay\Clients\ClientMapper;
use Maishapay\Clients\ClientTransformer;
use Maishapay\Customers\CustomerMapper;
use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Maishapay\Users\UserMapper;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class EditClientAction
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
        $client_uuid = $request->gettAttribue('id');
        $data = $request->getParsedBody();
        $this->logger->info("Updating an client", ['client_uuid' => $client_uuid, 'data' => $data]);

        $customer = $this->customerMapper->loadById($client_uuid);
        $client = $this->clientMapper->loadById($client_uuid);
        $user = $this->userMapper->loadById($client_uuid);

        if (!$client && !$customer && !$user) {
            $problem = new ApiProblem(
                'Could not find client',
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                404
            );

            throw new ProblemException($problem);
        } else if (!$data) {
            $problem = new ApiProblem(
                'Body is empty',
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                404
            );

            throw new ProblemException($problem);
        }

        $customer->update($data);
        $this->customerMapper->update($customer);

        $client->update($data);
        $this->clientMapper->update($client);

        $user->update($data);
        $this->userMapper->update($user);

        $transformer = new ClientTransformer();
        $hal = $transformer->transform($client);

        $response = $this->renderer->render($request, $response, $hal);
        return $response->withStatus(200);
    }
}
