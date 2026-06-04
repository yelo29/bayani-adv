<?php

require_once 'User.php';
require_once 'SessionManager.php';

/**
 * Admin Class
 * Inheritance: Extends User class
 * Encapsulation: Manages admin authentication
 */
class Admin extends User {
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

        try {
            $admin = $this->db->fetchOne(
                "SELECT id, email, password FROM admins WHERE email = :email",
                [':email' => $email]
            );

            // Note: Using plain text comparison for now (should use password_verify in production)
            if ($admin && $password === $admin['password']) {
                $this->sessionManager->setAdminId($admin['id']);
                $_SESSION['admin_email'] = $admin['email'];
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
            'logged_in' => $this->sessionManager->isAdminLoggedIn(),
            'admin_email' => $_SESSION['admin_email'] ?? null
        ];
    }

    private function loadFromSession() {
        $this->id = $this->sessionManager->getAdminId();
        $this->email = $_SESSION['admin_email'] ?? null;
    }

    public function requireLogin() {
        $this->sessionManager->requireAdminLogin();
    }

    public function getAdminId() {
        return $this->sessionManager->getAdminId();
    }
}
