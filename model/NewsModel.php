<?php
// Prevent direct script access
defined('BASEPATH') or exit('No direct script access allowed');

// Include database connection
require_once 'db/connect.php';

class NewsModel {

    // Retrieve all news items from the database
    public function getAll() {
        global $pdo;

        // Join news with users to get author information
        $sql = "SELECT 
                    news.*, 
                    users.name AS author_name, 
                    users.email AS author_email
                FROM news
                INNER JOIN users ON news.user_id = users.id
                ORDER BY news.published_at DESC"; // Sort by latest first

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve a single news item by its slug including author info
    public function getBySlug($slug) {
        global $pdo;

        // Prepare SQL query to fetch news and author details by slug
        $sql = "SELECT 
                    news.*, 
                    users.name AS author_name, 
                    users.email AS author_email
                FROM news
                INNER JOIN users ON news.user_id = users.id
                WHERE news.slug = :slug
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Retrieve a single news item by its id
    public function getById($id) {
        global $pdo;

        // Prepare and execute a query to fetch one news item by ID
        $stmt = $pdo->prepare("SELECT * FROM news WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new news item
    public function create($data) {
        global $pdo;

        // Prepare SQL insert statement with named placeholders
        $sql = "INSERT INTO news 
            (title, slug, published_at, user_id, intro, content, image_path) 
            VALUES 
            (:title, :slug, :published_at, :user_id, :intro, :content, :image_path)";

        $stmt = $pdo->prepare($sql);

        // Execute statement with bound values using session user as author
        $stmt->execute([
            'title'        => $data['title'],
            'slug'         => $data['slug'],
            'published_at' => $data['published_at'],
            'user_id'      => $_SESSION['user']['id'], // Set author from session
            'intro'        => $data['intro'],
            'content'      => $data['content'],
            'image_path'   => $data['image_path']
        ]);
    }

    // Update an existing news item by its id
    public function update($id, $data) {
        global $pdo;

        // Prepare SQL update query
        $sql = "UPDATE news SET 
                    title = :title,
                    slug = :slug,
                    published_at = :published_at,
                    intro = :intro,
                    content = :content,
                    image_path = :image_path
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        // Execute update with the provided data
        return $stmt->execute([
            'title'        => $data['title'],
            'slug'         => $data['slug'],
            'published_at' => $data['published_at'],
            'intro'        => $data['intro'],
            'content'      => $data['content'],
            'image_path'   => $data['image_path'],
            'id'           => $id
        ]);
    }

    // Delete a news item by its slug
    public function deleteBySlug($slug) {
        global $pdo;

        // Prepare and execute delete query by slug
        $stmt = $pdo->prepare("DELETE FROM news WHERE slug = :slug LIMIT 1");
        return $stmt->execute(['slug' => $slug]);
    }

    // Delete a news item by its id
    public function deleteById($id) {
        global $pdo;

        // Prepare and execute delete query by ID
        $stmt = $pdo->prepare("DELETE FROM news WHERE id = :id LIMIT 1");
        return $stmt->execute(['id' => $id]);
    }
}
