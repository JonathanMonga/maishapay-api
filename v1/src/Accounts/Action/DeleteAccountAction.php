<?php
namespace Maishapay\Accounts\Action;

use Maishapay\Accounts\AccountMapper;
use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Maishapay\Users\UserMapper;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class DeleteAccountAction
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
        $this->logger->info("Deleting an account", ['account_uuid' => $account_uuid]);

        $account = $this->accountMapper->loadById($account_uuid);

        if (!$account) {
            $problem = new ApiProblem(
                'Could not find account',
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                404
            );
            throw new ProblemException($problem);
        }

        $this->accountMapper->delete($account_uuid);

        return $response->withStatus(204);
    }
}
