<?php
namespace Maishapay\Customer;

use Nocarrier\Hal;

/**
 * Transform an Author (or collection of Authors) into Hal resource
 */
class CustomerTransformer
{
    public function transformCollection($customers)
    {
        $hal = new Hal('/customers');

        $count = 0;
        foreach ($customers as $customer) {
            $count++;
            $hal->addResource('customer', $this->transform($customer));
        }

        $hal->setData(['count' => $count]);

        return $hal;
    }

    public function transform(Customer $customer)
    {
        $data = $customer->getArrayCopy();

        $resource = new Hal('/customers/' . $data['customer_uuid'], $data);
        $resource->addLink('accounts', '/customers/' . $data['customer_uuid'] . '/accounts');

        return $resource;
    }
}
