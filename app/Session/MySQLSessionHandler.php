<?php
namespace App\Session;

use PDO;
use SessionHandlerInterface;

class MySQLSessionHandler implements SessionHandlerInterface {
    protected $pdo;
    protected $table;

    public function __construct(PDO $pdo, $table = 'sessions') {
        $this->pdo = $pdo;
        $this->table = $table;
    }

    // Called when a session is opened.
    public function open($savePath, $sessionName) {
        // No action necessary because we use PDO already connected.
        return true;
    }

    // Called when a session is closed.
    public function close() {
        // Close any resources if needed.
        return true;
    }

    // Read session data from the database.
    public function read($sessionId) {
        $stmt = $this->pdo->prepare("SELECT data FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $sessionId]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['data'];
        }
        return '';
    }

    // Write session data to the database.
    public function write($sessionId, $data) {
        $time = time();
        $stmt = $this->pdo->prepare("
            REPLACE INTO {$this->table} (id, data, timestamp)
            VALUES (:id, :data, :timestamp)
        ");
        return $stmt->execute([
            'id' => $sessionId,
            'data' => $data,
            'timestamp' => $time
        ]);
    }

    // Destroy a session.
    public function destroy($sessionId) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $sessionId]);
    }

    // Cleanup old sessions.
    public function gc($maxlifetime) {
        $old = time() - $maxlifetime;
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE timestamp < :old");
        return $stmt->execute(['old' => $old]);
    }
}
