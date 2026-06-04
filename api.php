<?php
header('Content-Type: application/json');
require_once 'config.php';

require_once __DIR__ . '/classes/Customer.php';
require_once __DIR__ . '/classes/Vote.php';
require_once __DIR__ . '/classes/ProductManager.php';
require_once __DIR__ . '/classes/CategoryManager.php';

$action = $_GET['action'] ?? '';

try {
    // Initialize Database with error handling
    $db = new Database($host, $dbname, $username, $password);

    // Initialize classes (Encapsulation)
    $customer = new Customer($db);
    $productManager = new ProductManager($db);
    $categoryManager = new CategoryManager($db);

    switch($action) {
        case 'register':
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $customer->register($data['email'] ?? '', $data['password'] ?? '');
            echo json_encode($result);
            break;
        case 'login':
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $customer->login($data['email'] ?? '', $data['password'] ?? '');
            echo json_encode($result);
            break;
        case 'logout':
            $result = $customer->logout();
            echo json_encode($result);
            break;
        case 'check_auth':
            $result = $customer->checkAuth();
            echo json_encode($result);
            break;
        case 'get_products':
            getProducts($productManager, $customer);
            break;
        case 'get_featured':
            getFeaturedProducts($productManager, $customer);
            break;
        case 'get_product_details':
            getProductDetails($productManager, $customer);
            break;
        case 'vote':
            voteProduct($customer, $db);
            break;
        case 'check_vote':
            checkVote($customer, $db);
            break;
        case 'get_categories':
            $categories = $categoryManager->getAll();
            echo json_encode($categories);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

function getProducts($productManager, $customer) {
    global $db;
    $categorySlug = $_GET['sector'] ?? 'all';
    $products = $productManager->getAll($categorySlug);
    
    $userId = $customer->getUserId();
    $vote = new Vote($db, $userId);
    $votedProducts = $vote->getUserVotedProducts();
    
    foreach ($products as &$product) {
        $product['user_voted'] = in_array($product['id'], $votedProducts);
    }
    
    echo json_encode($products);
}

function getFeaturedProducts($productManager, $customer) {
    global $db;
    $products = $productManager->getFeatured(4);
    
    $userId = $customer->getUserId();
    $vote = new Vote($db, $userId);
    $votedProducts = $vote->getUserVotedProducts();
    
    foreach ($products as &$product) {
        $product['user_voted'] = in_array($product['id'], $votedProducts);
    }
    
    echo json_encode($products);
}

function getProductDetails($productManager, $customer) {
    global $db;
    $productId = $_GET['product_id'] ?? 0;
    $product = $productManager->getById($productId);
    
    if (!$product) {
        echo json_encode(['error' => 'Product not found']);
        return;
    }
    
    $userId = $customer->getUserId();
    $vote = new Vote($db, $userId);
    $voteResult = $vote->check($productId);
    
    $product['user_voted'] = $voteResult['voted'];
    
    echo json_encode($product);
}

function voteProduct($customer, $db) {
    $customer->requireLogin();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'Invalid request method']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    $productId = $data['product_id'] ?? 0;
    $userId = $customer->getUserId();
    
    $vote = new Vote($db, $userId);
    $result = $vote->toggle($productId);
    
    echo json_encode($result);
}

function checkVote($customer, $db) {
    $customer->requireLogin();
    
    $productId = $_GET['product_id'] ?? 0;
    $userId = $customer->getUserId();
    
    $vote = new Vote($db, $userId);
    $result = $vote->check($productId);
    
    echo json_encode($result);
}
?>