<?php
// Prevent direct script access
defined('BASEPATH') or exit('No direct script access allowed');

// Include DB connection
require_once 'db/connect.php';

class UserModel {
    // Get user by email address
    public function getByEmail($email) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
