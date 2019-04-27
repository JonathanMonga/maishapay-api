<?php
namespace Maishapay\Clients;

use Monolog\Logger;

class ClientMapper
{
    protected $logger;
    protected $db;

    public function __construct(Logger $logger, \PDO $db)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    /**
     * Fetch all clients
     *
     * @return [clients]
     */
    public function fetchAll()
    {
        $sql = "SELECT * FROM oauth_clients";
        $stmt = $this->db->query($sql);

        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new Client($row);
        }

        return $results;
    }

    /**
     * Load a single Client
     *
     * @param $client_id
     * @return false|Client
     */
    public function loadById($client_id)
    {
        $sql = "SELECT * FROM oauth_clients WHERE client_id = :client_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['client_id' => $client_id]);
        $data = $stmt->fetch();

        if ($data) {
            return new Client($data);
        }

        return false;
    }

    /**
     * Create an Client
     *
     * @param Client $client
     * @return Client
     */
    public function insert(Client $client)
    {
        $data = $client->getArrayCopy();
        $data['created'] = date('Y-m-d H:i:s');
        $data['updated'] = $data['created'];

        $query =
            "INSERT INTO oauth_clients (client_id, 
                                    client_secret, 
                                    redirect_uri, 
                                    grant_types, 
                                    scope, 
                                    user_id, 
                                    client_status, 
                                    call_limit, 
                                    created, 
                                    updated)
                                         
            VALUES (:client_uuid, 
                    :client_secret, 
                    :redirect_uri, 
                    :grant_types, 
                    :scope, 
                    :client_uuid, 
                    :client_status, 
                    :call_limit, 
                    :created, 
                    :updated)";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return new Client($data);
    }

    /**
     * Update an author
     *
     * @param Client $client
     * @return Client
     */
    public function update(Client $client)
    {
        $data = $client->getArrayCopy();
        $data['updated'] = date('Y-m-d H:i:s');

        $query = "UPDATE oauth_clients
            SET client_secret =  :client_secret, 
                client_status =     :client_status, 
                call_limit =            :call_limit, 
                redirect_uri =            :redirect_uri
            WHERE client_id = :client_id";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return new Client($data);
    }

    /**
     * Delete an Client
     *
     * @param $client_id
     * @return bool True if there was an Clients to delete
     */
    public function delete($client_id)
    {
        $data['client_id'] = $client_id;
        $query = "DELETE FROM oauth_clients WHERE client_id = :client_id";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return (bool)$stmt->rowCount();
    }
}
