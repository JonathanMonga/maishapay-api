<?php
namespace Maishapay\Transactions;

use Nocarrier\Hal;

/**
 * Transform an Author (or collection of Authors) into Hal resource
 */
class ClientTransformer
{
    public function transformCollection($clients)
    {
        $hal = new Hal('/clients');

        $count = 0;
        foreach ($clients as $client) {
            $count++;
            $hal->addResource('client', $this->transform($client));
        }

        $hal->setData(['count' => $count]);

        return $hal;
    }

    public function transform(Client $client) {
        $data = $client->getArrayCopy();

        $resource = new Hal('/clients/' . $data['client_uuid'], $data);
        $resource->addLink('projects', '/client/' . $data['client_uuid'] . '/projects');

        return $resource;
    }
}
