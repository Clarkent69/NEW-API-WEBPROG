<?php
require_once __DIR__ . '/config.php';

class UserServices {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function addUser($username) {
        $stmt = $this->pdo->prepare("INSERT INTO users (username) VALUES (?)");
        if ($stmt->execute([$username])) {
            return true;
        }
        return false;
    }
}
?>
