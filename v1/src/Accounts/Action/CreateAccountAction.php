<?php
namespace Maishapay\Accounts\Action;

use Maishapay\Accounts\{
    Account, AccountMapper, AccountTransformer
};

use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class CreateAccountAction
{
    protected $logger;
    protected $renderer;
    protected $accountMapper;

    public function __construct(Logger $logger,
                                HalRenderer $renderer,
                                AccountMapper $accountMapper)
    {
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->accountMapper = $accountMapper;
    }

    public function __invoke($request, $response)
    {
        $data = $request->getParsedBody();
        $this->logger->info("Creating a new account", ['data' => $data]);

        $account = new Account($data);
        $this->accountMapper->insert($account);

        $transformer = new AccountTransformer();
        $hal = $transformer->transform($account);

        $response = $this->renderer->render($request, $response, $hal);
        return $response->withStatus(201);
    }
}
