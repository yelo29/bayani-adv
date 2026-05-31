<?php
header('Content-Type: application/json');
require_once 'config.php';

$action = $_GET['action'] ?? '';

switch($action) {
    case 'register':
        register();
        break;
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'check_auth':
        checkAuth();
        break;
    case 'get_products':
        getProducts();
        break;
    case 'get_featured':
        getFeaturedProducts();
        break;
    case 'get_product_details':
        getProductDetails();
        break;
    case 'vote':
        voteProduct();
        break;
    case 'check_vote':
        checkVote();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function register() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'Invalid request method']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        echo json_encode(['error' => 'Email and password are required']);
        return;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email format']);
        return;
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['error' => 'Password must be at least 6 characters']);
        return;
    }
    
    try {
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $checkStmt->bindParam(':email', $email);
        $checkStmt->execute();
        
        if ($checkStmt->fetch()) {
            echo json_encode(['error' => 'Email already registered']);
            return;
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $insertStmt->bindParam(':email', $email);
        $insertStmt->bindParam(':password', $hashedPassword);
        $insertStmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Registration successful']);
        
    } catch(Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function login() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'Invalid request method']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        echo json_encode(['error' => 'Email and password are required']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            echo json_encode(['success' => true, 'message' => 'Login successful']);
        } else {
            echo json_encode(['error' => 'Invalid email or password']);
        }
        
    } catch(Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function logout() {
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
}

function checkAuth() {
    echo json_encode([
        'logged_in' => isLoggedIn(),
        'user_email' => $_SESSION['user_email'] ?? null
    ]);
}

function getProducts() {
    global $pdo;
    $sector = $_GET['sector'] ?? 'all';
    
    $sql = "SELECT id, title, origin, tag, tag_class, description, image_url, sector, vote_count 
            FROM products";
    
    if ($sector !== 'all') {
        if ($sector === 'popular') {
            $sql .= " ORDER BY vote_count DESC";
        } else {
            $sql .= " WHERE sector = :sector";
        }
    }
    
    $stmt = $pdo->prepare($sql);
    
    if ($sector !== 'all' && $sector !== 'popular') {
        $stmt->bindParam(':sector', $sector);
    }
    
    $stmt->execute();
    $products = $stmt->fetchAll();
    
    $userId = getUserId();
    $votedProducts = [];
    
    if ($userId) {
        try {
            $voteStmt = $pdo->prepare("SELECT product_id FROM votes WHERE user_id = :uid");
            $voteStmt->bindParam(':uid', $userId);
            $voteStmt->execute();
            $voteResults = $voteStmt->fetchAll(PDO::FETCH_ASSOC);
            $votedProducts = array_column($voteResults, 'product_id');
        } catch (Exception $e) {
            $votedProducts = [];
        }
    }
    
    foreach ($products as &$product) {
        $product['user_voted'] = in_array($product['id'], $votedProducts);
    }
    
    echo json_encode($products);
}

function getFeaturedProducts() {
    global $pdo;
    
    $sql = "SELECT id, title, origin, tag, tag_class, description, image_url, sector, vote_count 
            FROM products 
            ORDER BY vote_count DESC 
            LIMIT 4";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll();
    
    $userId = getUserId();
    $votedProducts = [];
    
    if ($userId) {
        try {
            $voteStmt = $pdo->prepare("SELECT product_id FROM votes WHERE user_id = :uid");
            $voteStmt->bindParam(':uid', $userId);
            $voteStmt->execute();
            $voteResults = $voteStmt->fetchAll(PDO::FETCH_ASSOC);
            $votedProducts = array_column($voteResults, 'product_id');
        } catch (Exception $e) {
            $votedProducts = [];
        }
    }
    
    foreach ($products as &$product) {
        $product['user_voted'] = in_array($product['id'], $votedProducts);
    }
    
    echo json_encode($products);
}

function getProductDetails() {
    global $pdo;
    
    $productId = $_GET['product_id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :pid");
    $stmt->bindParam(':pid', $productId);
    $stmt->execute();
    $product = $stmt->fetch();
    
    if (!$product) {
        echo json_encode(['error' => 'Product not found']);
        return;
    }
    
    $userId = getUserId();
    $hasVoted = false;
    
    if ($userId) {
        try {
            $voteStmt = $pdo->prepare("SELECT id FROM votes WHERE product_id = :pid AND user_id = :uid");
            $voteStmt->bindParam(':pid', $productId);
            $voteStmt->bindParam(':uid', $userId);
            $voteStmt->execute();
            $hasVoted = $voteStmt->fetch() !== false;
        } catch (Exception $e) {
            $hasVoted = false;
        }
    }
    
    $product['user_voted'] = $hasVoted;
    
    echo json_encode($product);
}

function voteProduct() {
    global $pdo;
    
    requireLogin();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'Invalid request method']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    $productId = $data['product_id'] ?? 0;
    $userId = getUserId();
    
    try {
        $pdo->beginTransaction();
        
        $checkStmt = $pdo->prepare("SELECT id FROM votes WHERE product_id = :pid AND user_id = :uid");
        $checkStmt->bindParam(':pid', $productId);
        $checkStmt->bindParam(':uid', $userId);
        $checkStmt->execute();
        
        if ($checkStmt->fetch()) {
            $deleteStmt = $pdo->prepare("DELETE FROM votes WHERE product_id = :pid AND user_id = :uid");
            $deleteStmt->bindParam(':pid', $productId);
            $deleteStmt->bindParam(':uid', $userId);
            $deleteStmt->execute();
            
            $updateStmt = $pdo->prepare("UPDATE products SET vote_count = vote_count - 1 WHERE id = :pid");
            $updateStmt->bindParam(':pid', $productId);
            $updateStmt->execute();
            
            $voted = false;
        } else {
            $insertStmt = $pdo->prepare("INSERT INTO votes (product_id, user_id) VALUES (:pid, :uid)");
            $insertStmt->bindParam(':pid', $productId);
            $insertStmt->bindParam(':uid', $userId);
            $insertStmt->execute();
            
            $updateStmt = $pdo->prepare("UPDATE products SET vote_count = vote_count + 1 WHERE id = :pid");
            $updateStmt->bindParam(':pid', $productId);
            $updateStmt->execute();
            
            $voted = true;
        }
        
        $pdo->commit();
        
        $countStmt = $pdo->prepare("SELECT vote_count FROM products WHERE id = :pid");
        $countStmt->bindParam(':pid', $productId);
        $countStmt->execute();
        $voteCount = $countStmt->fetchColumn();
        
        echo json_encode([
            'success' => true,
            'voted' => $voted,
            'vote_count' => $voteCount
        ]);
        
    } catch(Exception $e) {
        $pdo->rollBack();
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function checkVote() {
    global $pdo;
    
    requireLogin();
    
    $productId = $_GET['product_id'] ?? 0;
    $userId = getUserId();
    
    $stmt = $pdo->prepare("SELECT id FROM votes WHERE product_id = :pid AND user_id = :uid");
    $stmt->bindParam(':pid', $productId);
    $stmt->bindParam(':uid', $userId);
    $stmt->execute();
    
    $hasVoted = $stmt->fetch() !== false;
    
    echo json_encode(['voted' => $hasVoted]);
}
?>