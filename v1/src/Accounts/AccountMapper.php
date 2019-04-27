<?php
namespace Maishapay\Accounts;

use Monolog\Logger;

class AccountMapper
{
    protected $logger;
    protected $db;

    public function __construct(Logger $logger, \PDO $db)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    /**
     * Fetch all accounts
     *
     * @return [accounts]
     */
    public function fetchAll()
    {
        $sql = "SELECT * FROM accounts ORDER BY account_id ASC";
        $stmt = $this->db->query($sql);

        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new Account($row);
        }

        return $results;
    }

    /**
     * Load a single Accounts
     *
     * @param $account_uuid
     * @return false|Account
     */
    public function loadById($account_uuid)
    {
        $sql = "SELECT * FROM accounts WHERE account_uuid = :account_uuid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['account_uuid' => $account_uuid]);
        $data = $stmt->fetch();

        if ($data) {
            return new Account($data);
        }

        return false;
    }

    /**
     * Create an Account
     *
     * @param Account $account
     * @return Account
     */
    public function insert(Account $account)
    {
        $data = $account->getArrayCopy();
        $data['created'] = date('Y-m-d H:i:s');
        $data['updated'] = $data['created'];

        $query =
            "INSERT INTO accounts (      account_uuid, 
                                         customer_uuid, 
                                         account_type, 
                                         default_balance, 
                                         default_currency, 
                                         local_balance, 
                                         local_currency, 
                                         default_balance_sent, 
                                         default_balance_receive, 
                                         local_balance_sent, 
                                         local_balance_receive, 
                                         account_status, 
                                         last_transfer, 
                                         saving_start_day, 
                                         saving_end_day, 
                                         created, 
                                         updated)
                                         
            VALUES (:account_uuid, 
                    :customer_uuid, 
                    :account_type, 
                    :default_balance, 
                    :default_currency, 
                    :local_balance, 
                    :local_currency, 
                    :default_balance_sent, 
                    :default_balance_receive, 
                    :local_balance_sent, 
                    :local_balance_receive, 
                    :account_status, 
                    :last_transfer, 
                    :saving_start_day, 
                    :saving_end_day,
                    :created, 
                    :updated)";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return new Account($data);
    }

    /**
     * Update an Account
     *
     * @param Account $account
     * @return Account
     */
    public function update(Account $account)
    {
        $data = $account->getArrayCopy();
        $data['updated'] = date('Y-m-d H:i:s');

        $query = "UPDATE accounts
            SET  customer_uuid = :customer_uuid, 
                 account_type =  :account_type, 
                 default_balance =  :default_balance, 
                 default_currency = :default_currency, 
                 local_balance =  :local_balance, 
                 local_currency =  :local_currency, 
                 default_balance_sent = :default_balance_sent, 
                 default_balance_receive = :default_balance_receive, 
                 local_balance_sent = :local_balance_sent, 
                 local_balance_receive = :local_balance_receive, 
                 account_status = :account_status, 
                 last_transfer = :last_transfer, 
                 saving_start_day = :saving_start_day, 
                 saving_end_day = :saving_end_day, 
                 created =        :created, 
                 updated =        :updated
            WHERE account_uuid = :account_uuid";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return new Account($data);
    }

    /**
     * Delete an Account
     *
     * @param $account_uuid
     * @return bool True if there was an Account to delete
     */
    public function delete($account_uuid)
    {
        $data['account_uuid'] = $account_uuid;
        $query = "DELETE FROM accounts WHERE account_uuid = :account_uuid";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return (bool)$stmt->rowCount();
    }
}
