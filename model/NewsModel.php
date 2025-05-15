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
        $stmt = $pdo->prepare("SELECT * FROM news WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new news item
    public function create($data) {
        global $pdo;

        // Prepare SQL insert statement with named placeholders
        $sql = "INSERT INTO news 
            (title, slug, published_at, author, intro, content, image_path) 
            VALUES 
            (:title, :slug, :published_at, :author, :intro, :content, :image_path)";

        $stmt = $pdo->prepare($sql);

        // Execute statement with bound values
        $stmt->execute([
            'title'        => $data['title'],
            'slug'         => $data['slug'],
            'published_at' => $data['published_at'],
            'author'       => $data['author'],
            'intro'        => $data['intro'],
            'content'      => $data['content'],
            'image_path'   => $data['image_path']
        ]);
    }
}
