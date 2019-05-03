<?php
namespace Maishapay\Accounts\Action;

use Maishapay\Accounts\AccountMapper;
use Maishapay\Accounts\AccountTransformer;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;

class GetAllAccountsAction
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
        $this->logger->info("Listing all accounts");

        $checklists = $this->accountMapper->fetchAll();

        $transformer = new AccountTransformer();
        $hal = $transformer->transformCollection($checklists);

        return $this->renderer->render($request, $response, $hal);
    }
}
