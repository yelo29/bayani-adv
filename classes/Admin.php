<?php

require_once 'User.php';

/**
 * Admin Class
 * Inheritance: Extends User class
 * Encapsulation: Manages admin authentication
 */
class Admin extends User {
    private $sessionKey = 'admin_id';
    private $sessionEmailKey = 'admin_email';

    public function __construct($db) {
        parent::__construct($db);
        $this->loadFromSession();
    }

    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'error' => 'Email and password are required'];
        }

        if (!$this->validateEmail($email)) {
            return ['success' => false, 'error' => 'Invalid email format'];
        }

        try {
            $admin = $this->db->fetchOne(
                "SELECT id, email, password FROM admins WHERE email = :email",
                [':email' => $email]
            );

            // Note: Using plain text comparison for now (should use password_verify in production)
            if ($admin && $password === $admin['password']) {
                $_SESSION[$this->sessionKey] = $admin['id'];
                $_SESSION[$this->sessionEmailKey] = $admin['email'];
                $this->id = $admin['id'];
                $this->email = $admin['email'];
                return ['success' => true, 'message' => 'Login successful'];
            } else {
                return ['success' => false, 'error' => 'Invalid email or password'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function logout() {
        session_destroy();
        return ['success' => true, 'message' => 'Logged out successfully'];
    }

    public function checkAuth() {
        return [
            'logged_in' => $this->isLoggedIn(),
            'admin_email' => $_SESSION[$this->sessionEmailKey] ?? null
        ];
    }

    private function loadFromSession() {
        $this->id = $_SESSION[$this->sessionKey] ?? null;
        $this->email = $_SESSION[$this->sessionEmailKey] ?? null;
    }

    private function isLoggedIn() {
        return isset($_SESSION[$this->sessionKey]);
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['error' => 'Authentication required']);
            exit;
        }
    }

    public function getAdminId() {
        return $this->id;
    }
}
