<?php
namespace App\Models;

use PDO;
use PDOException;
use App\Database;

class User {
    private $pdo;

    // Constructor accepts an existing PDO connection
    public function __construct(PDO $pdo = null) {
        // Use the global DB connection if one isn't provided.
        $this->pdo = $pdo ?? Database::connect();
    }

    // Find a user by email address
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new user record
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (
                email, 
                password_hash, 
                role, 
                onboarded, 
                verified, 
                created_at, 
                updated_at
            ) VALUES (
                :email, 
                :password_hash, 
                :role, 
                :onboarded, 
                :verified, 
                NOW(), 
                NOW()
            )
        ");
        return $stmt->execute([
            'email'         => $data['email'],
            'password_hash' => $data['password_hash'],
            'role'          => $data['role'],
            'onboarded'     => $data['onboarded'] ?? 0,
            'verified'      => $data['verified'] ?? 0,
        ]);
    }

    // Update login timestamps:
    // - Always update the last login attempt
    // - Optionally update last successful login if provided
    public function updateLoginTimestamps($id, $lastAttempt, $lastSuccess = null) {
        $sql = "UPDATE users 
                SET last_login_attempt = :last_login_attempt";
        if ($lastSuccess !== null) {
            $sql .= ", last_successful_login = :last_successful_login";
        }
        $sql .= ", updated_at = NOW() WHERE id = :id";

        $params = [
            'last_login_attempt' => $lastAttempt,
            'id' => $id,
        ];
        if ($lastSuccess !== null) {
            $params['last_successful_login'] = $lastSuccess;
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
