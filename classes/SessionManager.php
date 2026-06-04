<?php

/**
 * SessionManager Class
 * Encapsulation: Manages session operations
 */
class SessionManager {
    private $userIdKey = 'user_id';
    private $adminIdKey = 'admin_id';

    public function __construct() {
        // Don't start session here - let config.php handle it
        // This prevents duplicate session_start() calls
    }

    public function getUserId() {
        return $_SESSION[$this->userIdKey] ?? null;
    }

    public function getAdminId() {
        return $_SESSION[$this->adminIdKey] ?? null;
    }

    public function isLoggedIn() {
        return isset($_SESSION[$this->userIdKey]) || isset($_SESSION[$this->adminIdKey]);
    }

    public function isAdminLoggedIn() {
        return isset($_SESSION[$this->adminIdKey]);
    }

    public function isCustomerLoggedIn() {
        return isset($_SESSION[$this->userIdKey]);
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['error' => 'Authentication required']);
            exit;
        }
    }

    public function requireAdminLogin() {
        if (!$this->isAdminLoggedIn()) {
            http_response_code(401);
            echo json_encode(['error' => 'Admin authentication required']);
            exit;
        }
    }

    public function setUserId($userId) {
        $_SESSION[$this->userIdKey] = $userId;
    }

    public function setAdminId($adminId) {
        $_SESSION[$this->adminIdKey] = $adminId;
    }

    public function destroy() {
        session_destroy();
    }
}
