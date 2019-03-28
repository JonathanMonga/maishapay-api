<?php
namespace Bookshelf;

use Nocarrier\Hal;

/**
 * Transform an Author (or collection of Authors) into Hal resource
 */
class CustomerTransformer
{
    public function transformCollection($authors)
    {
        $hal = new Hal('/customers');

        $count = 0;
        foreach ($authors as $author) {
            $count++;
            $hal->addResource('customer', $this->transform($author));
        }

        $hal->setData(['count' => $count]);

        return $hal;
    }

    public function transform($author)
    {
        $data = $author->getArrayCopy();

        $resource = new Hal('/customers/' . $data['customer_uuid'], $data);
        $resource->addLink('accounts', '/customers/' . $data['customer_uuid'] . '/accounts');

        return $resource;
    }
}
