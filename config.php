<?php
session_start();

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/SessionManager.php';

$host = 'sql113.infinityfree.com';
$dbname = 'if0_42059838_gawang_pinas';
$username = 'if0_42059838';
$password = 'warframeyareli';

// Initialize SessionManager class (Encapsulation)
$session = new SessionManager();

// Database will be initialized in API files with proper error handling
?>