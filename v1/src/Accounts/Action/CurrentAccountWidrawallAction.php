<?php
namespace Maishapay\Accounts\Action;

use Maishapay\Accounts\AccountMapper;
use Maishapay\Accounts\AccountTransformer;
use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Maishapay\Users\UserMapper;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class EditAccountAction
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
        $account_uuid = $request->getAttribute('account_uuid');
        $data = $request->getParsedBody();
        $this->logger->info("Updating an account", ['account_uuid' => $account_uuid, 'data' => $data]);

        $account = $this->accountMapper->loadById($account_uuid);

        if (!$account) {
            $problem = new ApiProblem(
                'Could not find account',
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                404
            );
            throw new ProblemException($problem);
        }

        $account->update($data);
        $this->accountMapper->update($account);

        $transformer = new AccountTransformer();
        $hal = $transformer->transform($account);

        $response = $this->renderer->render($request, $response, $hal);
        return $response->withStatus(200);
    }
}
