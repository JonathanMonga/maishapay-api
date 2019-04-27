<?php
namespace Maishapay\Users;

use Monolog\Logger;

class UserMapper
{
    protected $logger;
    protected $db;

    public function __construct(Logger $logger, \PDO $db)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    /**
     * Fetch all Users
     *
     * @return [Users]
     */
    public function fetchAll()
    {
        $sql = "SELECT * FROM oauth_users";
        $stmt = $this->db->query($sql);

        $results = [];
        while ($row = $stmt->fetch()) {
            $row['customer_uuid'] = $row['username'];

            $results[] = new User($row);
        }

        return $results;
    }

    /**
     * Load a single Client
     *
     * @param $username
     * @return false|User
     */
    public function loadById($username)
    {
        $sql = "SELECT * FROM oauth_users WHERE `username` = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        $data = $stmt->fetch();

        if ($data) {
            $data['customer_uuid'] = $data['username'];

            return new User($data);
        }

        return false;
    }

    /**
     * Create an User
     *
     * @param User $user
     * @return User
     */
    public function insert(User $user)
    {
        $data = $user->getArrayCopy();
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created'] = date('Y-m-d H:i:s');
        $data['updated'] = $data['created'];

        $query =
            "INSERT INTO oauth_users (
                                    username, 
                                    password, 
                                    first_name, 
                                    last_name, 
                                    email, 
                                    email_verified, 
                                    scope, 
                                    created, 
                                    updated)
                                         
            VALUES (
                    :username, 
                    :password, 
                    :first_name, 
                    :last_name, 
                    :email, 
                    :email_verified, 
                    :scope, 
                    :created, 
                    :updated)";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return new User($data);
    }

    /**
     * Update an author
     *
     * @param User $user
     * @return User
     */
    public function update(User $user)
    {
        $data = $user->getArrayCopy();

        $updateData = [
            "username" =>  $data['customer_uuid'],
            "first_name" =>  $data['first_name'],
            "last_name" =>  $data['last_name'],
            "email" =>  $data['email'],
            "email_verified" =>  $data['email_verified'],
            "updated" =>  date('Y-m-d H:i:s'),
        ];

        $query = "UPDATE oauth_users
            SET first_name =       :first_name, 
                last_name =        :last_name, 
                email =            :email,
                email_verified =   :email_verified,
                updated =          :updated
            WHERE username = :username";

        $stmt = $this->db->prepare($query);
        $stmt->execute($updateData);

        return new User($data);
    }

    /**
     * Delete an User
     *
     * @param $username
     * @return bool True if there was an Clients to delete
     */
    public function delete($username)
    {
        $data['username'] = $username;
        $query = "DELETE FROM oauth_users WHERE username = :username";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        return (bool)$stmt->rowCount();
    }
}
