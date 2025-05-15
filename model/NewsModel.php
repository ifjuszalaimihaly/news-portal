<?php
// Prevent direct script access
defined('BASEPATH') or exit('No direct script access allowed');

// Include database connection
require_once 'db/connect.php';

class NewsModel {
    // Retrieve all news items from the database
    public function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM news");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve a single news item by its slug
    public function getBySlug($slug) {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM news WHERE slug  LIKE '$slug'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }
}
