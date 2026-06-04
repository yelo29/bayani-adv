<?php
header('Content-Type: application/json');
require_once 'config.php';

require_once __DIR__ . '/classes/Admin.php';
require_once __DIR__ . '/classes/ProductManager.php';
require_once __DIR__ . '/classes/CategoryManager.php';

$action = $_GET['action'] ?? '';

try {
    // Initialize Database with error handling
    $db = new Database($host, $dbname, $username, $password);

    // Initialize classes (Encapsulation)
    $admin = new Admin($db);
    $productManager = new ProductManager($db);
    $categoryManager = new CategoryManager($db);

    switch($action) {
        case 'admin_login':
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $admin->login($data['email'] ?? '', $data['password'] ?? '');
            echo json_encode($result);
            break;
        case 'admin_logout':
            $result = $admin->logout();
            echo json_encode($result);
            break;
        case 'check_admin_auth':
            $result = $admin->checkAuth();
            echo json_encode($result);
            break;
        case 'get_products':
            $admin->requireLogin();
            $products = $productManager->getAll();
            echo json_encode($products);
            break;
        case 'add_product':
            $admin->requireLogin();
            $data = [
                'title' => $_POST['title'] ?? '',
                'origin' => $_POST['origin'] ?? '',
                'tag' => $_POST['tag'] ?? '',
                'tag_class' => $_POST['tag_class'] ?? '',
                'category_id' => $_POST['category_id'] ?? '',
                'sector' => $_POST['sector'] ?? '',
                'description' => $_POST['description'] ?? '',
                'heritage_story' => $_POST['heritage_story'] ?? '',
                'where_to_find' => $_POST['where_to_find'] ?? '',
                'did_you_know' => $_POST['did_you_know'] ?? ''
            ];
            $imageFile = $_FILES['image'] ?? null;
            $result = $productManager->add($data, $imageFile);
            echo json_encode($result);
            break;
        case 'update_product':
            $admin->requireLogin();
            $id = $_POST['id'] ?? 0;
            $data = [
                'title' => $_POST['title'] ?? '',
                'origin' => $_POST['origin'] ?? '',
                'tag' => $_POST['tag'] ?? '',
                'tag_class' => $_POST['tag_class'] ?? '',
                'category_id' => $_POST['category_id'] ?? '',
                'sector' => $_POST['sector'] ?? '',
                'description' => $_POST['description'] ?? '',
                'heritage_story' => $_POST['heritage_story'] ?? '',
                'where_to_find' => $_POST['where_to_find'] ?? '',
                'did_you_know' => $_POST['did_you_know'] ?? ''
            ];
            $imageFile = $_FILES['image'] ?? null;
            $result = $productManager->update($id, $data, $imageFile);
            echo json_encode($result);
            break;
        case 'delete_product':
            $admin->requireLogin();
            $id = $_POST['id'] ?? 0;
            $result = $productManager->delete($id);
            echo json_encode($result);
            break;
        case 'get_categories':
            $admin->requireLogin();
            $categories = $categoryManager->getAll();
            echo json_encode($categories);
            break;
        case 'add_category':
            $admin->requireLogin();
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $categoryManager->add($data);
            echo json_encode($result);
            break;
        case 'update_category':
            $admin->requireLogin();
            $id = $_POST['id'] ?? 0;
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $categoryManager->update($id, $data);
            echo json_encode($result);
            break;
        case 'delete_category':
            $admin->requireLogin();
            $id = $_POST['id'] ?? 0;
            $result = $categoryManager->delete($id);
            echo json_encode($result);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>