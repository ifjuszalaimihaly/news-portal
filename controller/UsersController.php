<?php
// Prevent direct script access
defined('BASEPATH') or exit('No direct script access allowed');

// Include the User model
require_once 'model/UsersModel.php';

class UsersController {

    // Show the login form
    public function showLoginForm() {
        if (!isset($_SESSION['user'])) {
            require 'view/login_form.php';
        } else {
            header('Location: /index.php/news/list');
        }
    }

    // Handle AJAX login
    public function handleLogin() {
        header('Content-Type: application/json');

        // Only allow AJAX POST requests
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
        ) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request type.']);
            return;
        }

        // Read and sanitize input
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Email and password are required.']);
            return;
        }

        // Load user by email
        $model = new UserModel();
        $user = $model->getByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid email or password.']);
            return;
        }

        // Valid login — start session and store user info
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ];

        // Respond with success
        echo json_encode(['success' => true]);
    }

    // Logout and destroy session
    public function logout() {
        if (isset($_SESSION['user'])) {
            session_destroy();
            header('Location: /index.php/news/list');
            exit;
        }
    }
}