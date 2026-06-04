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

    public function getAll($categorySlug = 'all') {
        $sql = "SELECT p.id, p.title, p.origin, p.tag, p.tag_class, p.description, p.image_url, p.sector, p.vote_count, p.heritage_story, p.where_to_find, p.did_you_know, p.category_id, c.slug as category_slug
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id";

        if ($categorySlug !== 'all') {
            if ($categorySlug === 'popular') {
                $sql .= " ORDER BY p.vote_count DESC";
            } else {
                $sql .= " WHERE c.slug = :categorySlug";
            }
        }

        $params = ($categorySlug !== 'all' && $categorySlug !== 'popular') ? [':categorySlug' => $categorySlug] : [];
        $products = $this->db->fetchAll($sql, $params);

        return $products;
    }

    public function getFeatured($limit = 4) {
        $limit = (int)$limit;
        $sql = "SELECT p.id, p.title, p.origin, p.tag, p.tag_class, p.description, p.image_url, p.sector, p.vote_count, p.heritage_story, p.where_to_find, p.did_you_know, p.category_id, c.slug as category_slug
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.vote_count DESC 
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
        $required = ['title', 'origin', 'tag', 'tag_class', 'category_id', 'sector', 'description'];
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
                "INSERT INTO products (title, origin, tag, tag_class, category_id, description, image_url, sector, vote_count, heritage_story, where_to_find, did_you_know) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?)",
                [
                    $data['title'],
                    $data['origin'],
                    $data['tag'],
                    $data['tag_class'],
                    $data['category_id'],
                    $data['description'],
                    $imageUrl,
                    $data['sector'],
                    $data['heritage_story'] ?? '',
                    $data['where_to_find'] ?? '',
                    $data['did_you_know'] ?? ''
                ]
            );

            return ['success' => true, 'message' => 'Product added successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function update($id, $data, $imageFile = null) {
        $required = ['title', 'origin', 'tag', 'tag_class', 'category_id', 'sector', 'description'];
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
                "UPDATE products SET title = ?, origin = ?, tag = ?, tag_class = ?, category_id = ?, description = ?, image_url = ?, sector = ?, heritage_story = ?, where_to_find = ?, did_you_know = ? WHERE id = ?",
                [
                    $data['title'],
                    $data['origin'],
                    $data['tag'],
                    $data['tag_class'],
                    $data['category_id'],
                    $data['description'],
                    $imageUrl,
                    $data['sector'],
                    $data['heritage_story'] ?? '',
                    $data['where_to_find'] ?? '',
                    $data['did_you_know'] ?? '',
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
