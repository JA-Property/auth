<?php
namespace App\Models;

use PDO;
use App\Database;

class Customer
{
    protected $pdo;

    public function __construct(PDO $pdo = null) {
        $this->pdo = $pdo ?? Database::connect();
    }

    /**
     * Find a customer by account_number + billing_zip
     */
    public function findByAccountNumberAndZip($accountNumber, $billingZip) {
        $sql = "SELECT * FROM customers 
                WHERE account_number = :acc 
                  AND billing_zip    = :zip 
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'acc' => $accountNumber,
            'zip' => $billingZip
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * (Optional) If you want to store user_id in customers table
     */
    public function updateUserId($customerId, $userId) {
        $sql = "UPDATE customers 
                SET user_id = :user_id 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'id'      => $customerId
        ]);
    }
}
