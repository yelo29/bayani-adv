<?php

/**
 * ProductManager Class
 * Encapsulation: Manages product CRUD operations
 */
class ProductManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll($sector = 'all') {
        $sql = "SELECT id, title, origin, tag, tag_class, description, image_url, sector, vote_count 
                FROM products";

        if ($sector !== 'all') {
            if ($sector === 'popular') {
                $sql .= " ORDER BY vote_count DESC";
            } else {
                $sql .= " WHERE sector = :sector";
            }
        }

        $params = ($sector !== 'all' && $sector !== 'popular') ? [':sector' => $sector] : [];
        $products = $this->db->fetchAll($sql, $params);

        return $products;
    }

    public function getFeatured($limit = 4) {
        $limit = (int)$limit;
        $sql = "SELECT id, title, origin, tag, tag_class, description, image_url, sector, vote_count 
                FROM products 
                ORDER BY vote_count DESC 
                LIMIT {$limit}";

        $products = $this->db->fetchAll($sql, []);
        return $products;
    }

    public function getById($id) {
        $product = $this->db->fetchOne(
            "SELECT * FROM products WHERE id = :id",
            [':id' => $id]
        );
        return $product;
    }

    public function add($data, $imageFile = null) {
        $required = ['title', 'origin', 'tag', 'tag_class', 'sector', 'description'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'error' => 'All fields are required'];
            }
        }

        $imageUrl = $this->handleImageUpload($imageFile);
        if (!$imageUrl && $imageFile) {
            return ['success' => false, 'error' => 'Failed to upload image'];
        }

        if (!$imageUrl) {
            return ['success' => false, 'error' => 'Image is required'];
        }

        try {
            $this->db->execute(
                "INSERT INTO products (title, origin, tag, tag_class, description, image_url, sector, vote_count) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, 0)",
                [
                    $data['title'],
                    $data['origin'],
                    $data['tag'],
                    $data['tag_class'],
                    $data['description'],
                    $imageUrl,
                    $data['sector']
                ]
            );

            return ['success' => true, 'message' => 'Product added successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function update($id, $data, $imageFile = null) {
        $required = ['title', 'origin', 'tag', 'tag_class', 'sector', 'description'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'error' => 'All fields are required'];
            }
        }

        try {
            $currentProduct = $this->getById($id);
            if (!$currentProduct) {
                return ['success' => false, 'error' => 'Product not found'];
            }

            $imageUrl = $currentProduct['image_url'];

            if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
                $newImageUrl = $this->handleImageUpload($imageFile);
                if ($newImageUrl) {
                    if ($currentProduct['image_url'] && file_exists($currentProduct['image_url'])) {
                        unlink($currentProduct['image_url']);
                    }
                    $imageUrl = $newImageUrl;
                } else {
                    return ['success' => false, 'error' => 'Failed to upload image'];
                }
            }

            $this->db->execute(
                "UPDATE products SET title = ?, origin = ?, tag = ?, tag_class = ?, description = ?, image_url = ?, sector = ? WHERE id = ?",
                [
                    $data['title'],
                    $data['origin'],
                    $data['tag'],
                    $data['tag_class'],
                    $data['description'],
                    $imageUrl,
                    $data['sector'],
                    $id
                ]
            );

            return ['success' => true, 'message' => 'Product updated successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function delete($id) {
        try {
            $product = $this->getById($id);
            if (!$product) {
                return ['success' => false, 'error' => 'Product not found'];
            }

            if ($product['image_url'] && file_exists($product['image_url'])) {
                unlink($product['image_url']);
            }

            $this->db->execute(
                "DELETE FROM products WHERE id = ?",
                [$id]
            );

            return ['success' => true, 'message' => 'Product deleted successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function handleImageUpload($file) {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = 'uploads/products/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $uploadPath;
        }

        return null;
    }
}
