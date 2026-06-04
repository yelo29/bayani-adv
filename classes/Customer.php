<?php

require_once 'User.php';
require_once 'SessionManager.php';

/**
 * Customer Class
 * Inheritance: Extends User class
 * Encapsulation: Manages customer authentication
 */
class Customer extends User {
    private $sessionManager;

    public function __construct($db) {
        parent::__construct($db);
        $this->sessionManager = new SessionManager();
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
                $this->sessionManager->setUserId($user['id']);
                $_SESSION['user_email'] = $user['email'];
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
            'logged_in' => $this->sessionManager->isCustomerLoggedIn(),
            'user_email' => $_SESSION['user_email'] ?? null
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
        $this->id = $this->sessionManager->getUserId();
        $this->email = $_SESSION['user_email'] ?? null;
    }

    public function requireLogin() {
        $this->sessionManager->requireLogin();
    }

    public function getUserId() {
        return $this->sessionManager->getUserId();
    }
}
