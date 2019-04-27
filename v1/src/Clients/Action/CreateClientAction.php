<?php
namespace Maishapay\Clients\Action;

use Maishapay\Clients\{
    Client, ClientMapper, ClientTransformer
};
use Maishapay\Customers\Customer;
use Maishapay\Customers\CustomerMapper;
use Maishapay\Error\{
    ApiProblem, Exception\ProblemException
};
use Maishapay\Users\User;
use Maishapay\Users\UserMapper;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class CreateClientAction
{
    protected $logger;
    protected $renderer;
    protected $customerMapper;
    protected $clientMapper;
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

    public function __invoke($request, $response, $insertClient)
    {
        $data = $request->getParsedBody();

        $this->logger->info("Creating a new client", ['data' => $data]);

        if($data){
            $client = new Client($data);
            $customer = new Customer($data);
            $user = new User($data);

            $transformer = new ClientTransformer();

            $this->customerMapper->insert($customer);
            $this->userMapper->insert($user);
            $this->clientMapper->insert($client);

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