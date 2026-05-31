<?php
header('Content-Type: application/json');
require_once 'config.php';

$action = $_GET['action'] ?? '';

switch($action) {
    case 'get_products':
        getProducts();
        break;
    case 'add_product':
        addProduct();
        break;
    case 'update_product':
        updateProduct();
        break;
    case 'delete_product':
        deleteProduct();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function getProducts() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll();
    
    echo json_encode($products);
}

function addProduct() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'Invalid request method']);
        return;
    }
    
    $title = $_POST['title'] ?? '';
    $origin = $_POST['origin'] ?? '';
    $tag = $_POST['tag'] ?? '';
    $tag_class = $_POST['tag_class'] ?? '';
    $sector = $_POST['sector'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (empty($title) || empty($origin) || empty($tag) || empty($tag_class) || empty($sector) || empty($description)) {
        echo json_encode(['error' => 'All fields are required']);
        return;
    }
    
    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/products/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $image_url = $uploadPath;
        } else {
            echo json_encode(['error' => 'Failed to upload image']);
            return;
        }
    } else {
        echo json_encode(['error' => 'Image is required']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO products (title, origin, tag, tag_class, description, image_url, sector, vote_count) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->execute([$title, $origin, $tag, $tag_class, $description, $image_url, $sector]);
        
        echo json_encode(['success' => true, 'message' => 'Product added successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function updateProduct() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'Invalid request method']);
        return;
    }
    
    $id = $_POST['id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $origin = $_POST['origin'] ?? '';
    $tag = $_POST['tag'] ?? '';
    $tag_class = $_POST['tag_class'] ?? '';
    $sector = $_POST['sector'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (empty($id) || empty($title) || empty($origin) || empty($tag) || empty($tag_class) || empty($sector) || empty($description)) {
        echo json_encode(['error' => 'All fields are required']);
        return;
    }
    
    try {
        // Get current product data
        $stmt = $pdo->prepare("SELECT image_url FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $currentProduct = $stmt->fetch();
        
        $image_url = $currentProduct['image_url'];
        
        // Handle image upload if new image is provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/products/';
            
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                // Delete old image if it exists
                if ($currentProduct['image_url'] && file_exists($currentProduct['image_url'])) {
                    unlink($currentProduct['image_url']);
                }
                $image_url = $uploadPath;
            } else {
                echo json_encode(['error' => 'Failed to upload image']);
                return;
            }
        }
        
        $stmt = $pdo->prepare("UPDATE products SET title = ?, origin = ?, tag = ?, tag_class = ?, description = ?, image_url = ?, sector = ? WHERE id = ?");
        $stmt->execute([$title, $origin, $tag, $tag_class, $description, $image_url, $sector, $id]);
        
        echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function deleteProduct() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'Invalid request method']);
        return;
    }
    
    $id = $_POST['id'] ?? 0;
    
    if (empty($id)) {
        echo json_encode(['error' => 'Product ID is required']);
        return;
    }
    
    try {
        // Get product data to delete image
        $stmt = $pdo->prepare("SELECT image_url FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        
        if ($product) {
            // Delete image file
            if ($product['image_url'] && file_exists($product['image_url'])) {
                unlink($product['image_url']);
            }
            
            // Delete product from database
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
        } else {
            echo json_encode(['error' => 'Product not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
