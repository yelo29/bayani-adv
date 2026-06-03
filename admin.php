<?php
header('Content-Type: application/json');
require_once 'config.php';

require_once __DIR__ . '/classes/Admin.php';
require_once __DIR__ . '/classes/ProductManager.php';

session_start();

$action = $_GET['action'] ?? '';

// Initialize classes (Encapsulation)
$admin = new Admin($db);
$productManager = new ProductManager($db);

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
    default:
        echo json_encode(['error' => 'Invalid action']);
}
?>