<?php
namespace Maishapay\Accounts\Action;

use Maishapay\Accounts\AccountMapper;
use Maishapay\Accounts\AccountTransformer;
use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class GetAccountByUUIDAction
{
    protected $logger;
    protected $renderer;
    protected $accountMapper;

    public function __construct(Logger $logger, HalRenderer $renderer, AccountMapper $accountMapper)
    {
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->accountMapper = $accountMapper;
    }

    public function __invoke($request, $response)
    {
        $account_uuid = $request->getAttribute('account_uuid');
        $this->logger->info("Listing a single account", ['account_uuid' => $account_uuid]);

        $account = $this->accountMapper->loadById($account_uuid);

        if (!$account) {
            $problem = new ApiProblem(
                'Could not find account',
                'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                404
            );
            throw new ProblemException($problem);
        }

        $transformer = new AccountTransformer();
        $hal = $transformer->transform($account);

        return $this->renderer->render($request, $response, $hal);
    }
}
