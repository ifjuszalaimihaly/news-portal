<?php
// Prevent direct script access
defined('BASEPATH') or exit('No direct script access allowed');

// Include the News model
require_once 'model/NewsModel.php';

class NewsController {

    // Display the list of news items
    public function list() {
        $model = new NewsModel();              // Create an instance of the model
        $news_list = $model->getAll();         // Fetch all news items
        require 'view/news_list.php';          // Load the view to display the list
    }

    // Display a single news item based on the provided slug
    public function show($param) {
        $model = new NewsModel();              // Create an instance of the model
        $news_item = $model->getBySlug($param); // Fetch a news item by slug
        require 'view/news_details.php';       // Load the view to display the item
    }

    public function showForm($param) {
        $model = new NewsModel();              // Create an instance of the model
        if (!is_null($param)) {
            $news_item = $model->getBySlug($param); // Fetch a news item by slug
        }
        require 'view/news_form.php';          // Load the form view (for create or edit)
    }

    public function handleNewsForm() {
        header('Content-Type: application/json');

        // Optional: only accept AJAX requests
        if (
            !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
        ) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request type.']);
            return;
        }

        // Retrieve form fields
        $id = $_POST['id'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $intro = trim($_POST['intro'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $publishedAt = date('Y-m-d H:i:s');     // Use current timestamp as publication date

        // Validate required fields
        if ($title === '' || $intro === '' || $content === '') {
            http_response_code(400);
            echo json_encode(['error' => 'All fields are required.']);
            return;
        }

        // Generate slug from title and date
        $slug = $this->generateSlug($title, $publishedAt);

        // Handle file upload
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $imagePath = $this->storeImage();  // Save the uploaded image
        }

        // Save to database
        $model = new NewsModel();

        if (!is_null($id)) {
            $updated_news_item = $model->getById($id); // Load existing news item

            // Check ownership before editing
            if ($updated_news_item['user_id'] !== $_SESSION['user']['id']) {
                http_response_code(401);
                echo json_encode(['error' => 'Editing this article is not allowed for this user.']);
                return;
            }

            $this->deleteImage($updated_news_item);    // Delete previous image if needed


            // Update existing record
            $model->update(
                $id,
                [
                    'title'        => $title,
                    'slug'         => $slug,
                    'published_at' => $publishedAt,
                    'intro'        => $intro,
                    'content'      => $content,
                    'image_path'   => $imagePath
                ]
            );
        } else {
            // Create new record
            $model->create([
                'title'        => $title,
                'slug'         => $slug,
                'published_at' => $publishedAt,
                'intro'        => $intro,
                'content'      => $content,
                'image_path'   => $imagePath
            ]);
        }

        // Success response
        echo json_encode(['success' => true]);
    }

    public function deleteNews() {
        header('Content-Type: application/json');

        // Allow only AJAX POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request type.']);
            return;
        }

        // Get id from POST data
        $id = trim($_POST['id'] ?? '');

        if ($id === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Missing slug parameter.']);
            return;
        }

        // Load news item by id
        $model = new NewsModel();
        $news_item = $model->getById($id);     // Fetch item for validation and deletion

        if (!$news_item) {
            http_response_code(404);
            echo json_encode(['error' => 'News item not found.']);
            return;
        }

        // Check ownership before deletion
        if ($news_item['user_id'] !== $_SESSION['user']['id']) {
            http_response_code(401);
            echo json_encode(['error' => 'Deleting this article is not allowed for this user.']);
            return;
        }

        // Delete the image file if it exists
        if (!empty($news_item['image_path']) && file_exists($news_item['image_path'])) {
            $this->deleteImage($news_item);
        }

        // Delete the news item from the database
        $deleted = $model->deleteById($id);

        if ($deleted) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete news item from database.']);
        }
    }

    private function generateSlug($title, $publishedAt) {
        // Create a URL-friendly slug from title and publication date
        $date = date('Y-m-d', strtotime($publishedAt));
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
        $slug = trim($slug, '-');
        return $date . '-' . $slug;
    }

    private function storeImage() {
        $uploadDir = 'uploads/';
        $filename = basename($_FILES['image']['name']);
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];
        $maxSize = 2 * 1024 * 1024; // 2 MB

        // Validate file extension
        if (!in_array($extension, $allowed)) {
            http_response_code(400);
            echo json_encode(['error' => 'Only JPG and PNG files are allowed.']);
            return;
        }

        // Validate file size
        if ($_FILES['image']['size'] > $maxSize) {
            http_response_code(400);
            echo json_encode(['error' => 'The image must be smaller than 2 MB.']);
            return;
        }

        // Save the file with a unique name
        $newFileName = uniqid('news_', true) . '.' . $extension;
        $destination = $uploadDir . $newFileName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            http_response_code(500);
            echo json_encode(['error' => 'Image upload failed.']);
            return;
        }

        return $destination;
    }

    private function deleteImage($news_item) {
        // Delete the image file if it exists
        if (!empty($news_item['image_path']) && file_exists($news_item['image_path'])) {
            if (!unlink($news_item['image_path'])) {
                http_response_code(500);
                echo json_encode(['error' => 'News deleted, but image file could not be removed.']);
                return;
            }
        }
    }

}
