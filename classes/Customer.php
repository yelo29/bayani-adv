<?php

require_once 'User.php';

/**
 * Customer Class
 * Inheritance: Extends User class
 * Encapsulation: Manages customer authentication
 */
class Customer extends User {
    private $sessionKey = 'user_id';
    private $sessionEmailKey = 'user_email';

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

        if (!$this->validatePassword($password)) {
            return ['success' => false, 'error' => 'Password must be at least 6 characters'];
        }

        try {
            $user = $this->db->fetchOne(
                "SELECT id, email, password FROM users WHERE email = :email",
                [':email' => $email]
            );

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION[$this->sessionKey] = $user['id'];
                $_SESSION[$this->sessionEmailKey] = $user['email'];
                $this->id = $user['id'];
                $this->email = $user['email'];
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
            'user_email' => $_SESSION[$this->sessionEmailKey] ?? null
        ];
    }

    public function register($email, $password) {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'error' => 'Email and password are required'];
        }

        if (!$this->validateEmail($email)) {
            return ['success' => false, 'error' => 'Invalid email format'];
        }

        if (!$this->validatePassword($password)) {
            return ['success' => false, 'error' => 'Password must be at least 6 characters'];
        }

        try {
            $existing = $this->db->fetchOne(
                "SELECT id FROM users WHERE email = :email",
                [':email' => $email]
            );

            if ($existing) {
                return ['success' => false, 'error' => 'Email already registered'];
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->db->execute(
                "INSERT INTO users (email, password) VALUES (:email, :password)",
                [':email' => $email, ':password' => $hashedPassword]
            );

            return ['success' => true, 'message' => 'Registration successful'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
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

    public function getUserId() {
        return $this->id;
    }
}
