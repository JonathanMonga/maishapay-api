<?php
namespace Maishapay\Accounts;

use Nocarrier\Hal;

/**
 * Transform an Account (or collection of Accounts) into Hal resource
 */
class AccountTransformer
{
    public function transformCollection($accounts)
    {
        $hal = new Hal('/accounts');

        $count = 0;
        foreach ($accounts as $account) {
            $count++;
            $hal->addResource('account', $this->transform($account));
        }

        $hal->setData(['count' => $count]);

        return $hal;
    }

    public function transform(Account $account) {
        $data = $account->getArrayCopy();

        $resource = new Hal('/accounts/' . $data['accounts_uuid'], $data);

        $resource->addLink('transactions', '/account/' . $data['account_uuid'] . '/transactions');
        $resource->addLink('cards', '/account/' . $data['account_uuid'] . '/cards');

        return $resource;
    }
}
