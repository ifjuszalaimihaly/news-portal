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
                ORDER BY news.published_at DESC";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Retrieve a single news item by its slug including author info
    public function getBySlug($slug) {
        global $pdo;

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

        // Execute statement with bound values
        $stmt->execute([
            'title'        => $data['title'],
            'slug'         => $data['slug'],
            'published_at' => $data['published_at'],
            'user_id'      => $_SESSION['user']['id'],
            'intro'        => $data['intro'],
            'content'      => $data['content'],
            'image_path'   => $data['image_path']
        ]);
    }

    public function update($id, $data) {
        global $pdo;

        $sql = "UPDATE news SET 
                    title = :title,
                    slug = :slug,
                    published_at = :published_at,
                    intro = :intro,
                    content = :content,
                    image_path = :image_path
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

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

    public function deleteBySlug($slug) {
        global $pdo;

        $stmt = $pdo->prepare("DELETE FROM news WHERE slug = :slug LIMIT 1");
        return $stmt->execute(['slug' => $slug]);
    }


    public function deleteById($id) {
        global $pdo;

        $stmt = $pdo->prepare("DELETE FROM news WHERE id = :id LIMIT 1");
        return $stmt->execute(['id' => $id]);
    }
}
