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
        $sql = "SELECT * FROM customers ORDER BY customer_id ASC";
        $stmt = $this->db->query($sql);

        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new Customer($row);
        }

        return $results;
    }

    /**
     * Load a single Customers
     *
     * @param $customer_uuid
     * @return false|Customer
     */
    public function loadById($customer_uuid)
    {
        $sql = "SELECT * FROM customers WHERE customer_uuid = :customer_uuid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['customer_uuid' => $customer_uuid]);
        $data = $stmt->fetch();

        if ($data) {
            return new Customer($data);
        }

        return false;
    }

    /**
     * Create an Customers
     *
     * @param Customer $customer
     * @return Customer
     */
    public function insert(Customer $customer)
    {
        $data = $customer->getArrayCopy();
        $data['created'] = date('Y-m-d H:i:s');
        $data['updated'] = $data['created'];

        $query =
            "INSERT INTO customers (customer_uuid, 
                                         country_iso_code, 
                                         phone_area_code, 
                                         number_phone, 
                                         names, 
                                         email, 
                                         password, 
                                         customer_type, 
                                         number_of_account, 
                                         customer_status, 
                                         location, 
                                         created, 
                                         updated)
                                         
            VALUES (:customer_uuid, 
                    :country_iso_code, 
                    :phone_area_code, 
                    :number_phone, 
                    :names, 
                    :email, 
                    :password, 
                    :customer_type, 
                    :number_of_account, 
                    :customer_status, 
                    :created, 
                    :updated)";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return new Customer($data);
    }

    /**
     * Update an author
     *
     * @param Customer $customer
     * @return Customer
     */
    public function update(Customer $customer)
    {
        $data = $customer->getArrayCopy();
        $data['updated'] = date('Y-m-d H:i:s');

        $query = "UPDATE customers
            SET country_iso_code = :country_iso_code, 
                phone_area_code =  :phone_area_code, 
                number_phone =     :number_phone, 
                names =            :names, 
                email =            :email, 
                password =         :password, 
                customer_type =    :customer_type, 
                number_of_account =:number_of_account, 
                customer_status =  :customer_status, 
                created =          :created, 
                updated =          :updated
            WHERE customer_uuid = :customer_uuid";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return new Customer($data);
    }

    /**
     * Delete an Customers
     *
     * @param $customer_uuid
     * @return bool True if there was an Customers to delete
     * @internal param Id $uuid of Customers to delete
     */
    public function delete($customer_uuid)
    {
        $data['customer_uuid'] = $customer_uuid;
        $query = "DELETE FROM customers WHERE customer_uuid = :customer_uuid";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return (bool)$stmt->rowCount();
    }
}
