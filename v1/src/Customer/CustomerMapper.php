<?php
namespace Maishapay\Customer;

use Monolog\Logger;

class CustomerMapper
{
    protected $logger;
    protected $db;

    public function __construct(Logger $logger, \PDO $db)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    /**
     * Fetch all customers
     *
     * @return [customers]
     */
    public function fetchAll()
    {
        $sql = "SELECT * FROM customers ORDER BY customer_uuid ASC";
        $stmt = $this->db->query($sql);

        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new Customer($row);
        }

        return $results;
    }

    /**
     * Load a single Customer
     *
     * @return Customer|false
     */
    public function loadById($id)
    {
        $sql = "SELECT * FROM author WHERE customer_id = :customer_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['customer_id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new Customer($data);
        }

        return false;
    }

    /**
     * Create an Customer
     *
     * @return Customer
     */
    public function insert(Customer $customer)
    {
        $data = $customer->getArrayCopy();
        $data['created'] = date('Y-m-d H:i:s');
        $data['updated'] = $data['created'];

        $query = "INSERT INTO author (author_id, name, biography, date_of_birth, created, updated)
            VALUES (:author_id, :name, :biography, :date_of_birth, :created, :updated)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute($data);

        return new Customer($data);
    }

    /**
     * Update an author
     *
     * @return Author
     */
    public function update(Customer $author)
    {
        $data = $author->getArrayCopy();
        $data['updated'] = date('Y-m-d H:i:s');

        $query = "UPDATE author
            SET name = :name,
                biography = :biography,
                date_of_birth = :date_of_birth,
                created = :created,
                updated = :updated
            WHERE author_id = :author_id
            ";

        $stmt = $this->db->prepare($query);
        $result = $stmt->execute($data);

        return new Customer($data);
    }

    /**
     * Delete an Customer
     *
     * @param $uuid       Id of Customer to delete
     * @return boolean  True if there was an author to delete
     */
    public function delete($uuid)
    {
        $data['customer_uuid'] = $uuid;
        $query = "DELETE FROM customers WHERE customer_uuid = :customer_uuid";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return (bool)$stmt->rowCount();
    }
}
