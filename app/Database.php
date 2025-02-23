<?php
namespace App;

use PDO;
use PDOException;

class Database {
    private static $pdo;

    /**
     * Establish and return a PDO connection using environment variables.
     *
     * @return PDO
     */
    public static function connect() {
        if (!self::$pdo) {
            $host    = getenv('DB_HOST') ?: 'localhost';
            $dbname  = getenv('DB_NAME') ?: 'japrdjyn_auth';
            $user    = getenv('DB_USER') ?: 'japrdjyn_auth';
            $pass    = getenv('DB_PASS') ?: 'feeSVwR{hqr+';
            $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

            $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
            try {
                self::$pdo = new PDO($dsn, $user, $pass);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("DB Connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
