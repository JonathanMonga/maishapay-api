<?php
namespace Maishapay\Transactions;

use Monolog\Logger;

class TransactionMapper
{
    protected $logger;
    protected $db;

    public function __construct(Logger $logger, \PDO $db)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    /**
     * Fetch all transactions
     *
     * @return [transactions]
     */
    public function fetchAll()
    {
        $sql = "SELECT * FROM transactions";
        $stmt = $this->db->query($sql);

        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new Transaction($row);
        }

        return $results;
    }

    /**
     * Load a single Transaction
     *
     * @param $transaction_uuid
     * @return false|Transaction
     */
    public function loadById($transaction_uuid)
    {
        $sql = "SELECT * FROM transactions WHERE transaction_uuid = :transaction_uuid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['transaction_uuid' => $transaction_uuid]);
        $data = $stmt->fetch();

        if ($data) {
            return new Transaction($data);
        }

        return false;
    }

    /**
     * Create an Transaction
     *
     * @param Transaction $transaction
     * @return Transaction
     */
    public function insert(Transaction $transaction)
    {
        $data = $transaction->getArrayCopy();
        $data['created'] = date('Y-m-d H:i:s');
        $data['updated'] = $data['created'];

        $query =
            "INSERT INTO transactions (
            transaction_uuid,
            source_account_uuid,
            source_account_phone_area_code,
            source_account_phone_number,
            source_account_names,
            source_account_balance,
            source_account_currency,
            destination_account_uuid,
            destination_account_phone_area_code,
            destination_account_phone_number,
            destination_account_names,
            destination_account_balance,
            destination_account_currency,
            transaction_amount,
            transaction_currency,
            destination_description,
            source_description,
            transaction_fee,
            transaction_rate,
            source_latitude,
            source_longitude,
            transaction_date,
            transaction_type,
            periode_type,
            periode_start_date,
            periode_end_date,
            number_of_time,
            operation_status,
            created, 
            updated)
                                         
            VALUES (
            :transaction_uuid,
            :source_account_uuid,
            :source_account_phone_area_code,
            :source_account_phone_number,
            :source_account_names,
            :source_account_balance,
            :source_account_currency,
            :destination_account_uuid,
            :destination_account_phone_area_code,
            :destination_account_phone_number,
            :destination_account_names,
            :destination_account_balance,
            :destination_account_currency,
            :transaction_amount,
            :transaction_currency,
            :destination_description,
            :source_description,
            :transaction_fee,
            :transaction_rate,
            :source_latitude,
            :source_longitude,
            :transaction_date,
            :transaction_type,
            :periode_type,
            :periode_start_date,
            :periode_end_date,
            :number_of_time,
            :operation_status,
            :created, 
            :updated)";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return new Transaction($data);
    }

    /**
     * Update an author
     *
     * @param Transaction $transaction
     * @return Transaction
     */
    public function update(Transaction $transaction)
    {
        $data = $transaction->getArrayCopy();

        $query = "UPDATE transactions
            SET 
            source_account_uuid = :source_account_uuid,
            source_account_phone_area_code = :source_account_phone_area_code,
            source_account_phone_number = :source_account_phone_number,
            source_account_names = :source_account_names,
            source_account_balance = :source_account_balance,
            source_account_currency = :source_account_currency,
            destination_account_uuid = :destination_account_uuid,
            destination_account_phone_area_code = :destination_account_phone_area_code,
            destination_account_phone_number = :destination_account_phone_number,
            destination_account_names = :destination_account_names,
            destination_account_balance = :destination_account_balance,
            destination_account_currency = :destination_account_currency,
            transaction_amount = :transaction_amount,
            transaction_currency = :transaction_currency,
            destination_description = :destination_description,
            source_description = :source_description,
            transaction_fee = :transaction_fee,
            transaction_rate = :transaction_rate,
            source_latitude = :source_latitude,
            source_longitude = :source_longitude,
            transaction_date = :transaction_date,
            transaction_type = :transaction_type,
            periode_type = :periode_type,
            periode_start_date = :periode_start_date,
            periode_end_date = :periode_end_date,
            number_of_time = :number_of_time,
            operation_status = :operation_status,
                updated =            :updated
            WHERE transaction_uuid = :transaction_uuid";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return new Transaction($data);
    }

    /**
     * Delete an Transaction
     *
     * @param $transaction_uuid
     * @return bool True if there was an Transactions to delete
     */
    public function delete($transaction_uuid)
    {
        $data['transaction_uuid'] = $transaction_uuid;
        $query = "DELETE FROM transactions WHERE transaction_uuid = :transaction_uuid";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return (bool)$stmt->rowCount();
    }
}
