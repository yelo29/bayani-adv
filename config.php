<?php
session_start();

require_once __DIR__ . '/classes/Database.php';

$host = 'host';
$dbname = 'database_name';
$username = 'root';
$password = '';

// Initialize Database class (Encapsulation)
$db = new Database($host, $dbname, $username, $password);

// Get PDO connection for backward compatibility
$pdo = $db->getConnection();

// Get current user ID from session
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Require login - returns error if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        exit;
    }
}
?>