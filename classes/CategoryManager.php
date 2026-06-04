<?php

/**
 * CategoryManager Class
 * Encapsulation: Manages category CRUD operations
 */
class CategoryManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $sql = "SELECT id, name, slug, description, created_at FROM categories ORDER BY name ASC";
        $categories = $this->db->fetchAll($sql, []);
        return $categories;
    }

    public function getById($id) {
        $category = $this->db->fetchOne(
            "SELECT * FROM categories WHERE id = :id",
            [':id' => $id]
        );
        return $category;
    }

    public function add($data) {
        $required = ['name', 'slug'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'error' => 'Name and slug are required'];
            }
        }

        try {
            $this->db->execute(
                "INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)",
                [
                    $data['name'],
                    $data['slug'],
                    $data['description'] ?? ''
                ]
            );

            return ['success' => true, 'message' => 'Category added successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function update($id, $data) {
        $required = ['name', 'slug'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'error' => 'Name and slug are required'];
            }
        }

        try {
            $this->db->execute(
                "UPDATE categories SET name = ?, slug = ?, description = ? WHERE id = ?",
                [
                    $data['name'],
                    $data['slug'],
                    $data['description'] ?? '',
                    $id
                ]
            );

            return ['success' => true, 'message' => 'Category updated successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function delete($id) {
        try {
            // Check if category is being used by products
            $productCount = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM products WHERE category_id = :category_id",
                [':category_id' => $id]
            );

            if ($productCount && $productCount['count'] > 0) {
                return ['success' => false, 'error' => 'Cannot delete category that is in use by products'];
            }

            $this->db->execute(
                "DELETE FROM categories WHERE id = ?",
                [$id]
            );

            return ['success' => true, 'message' => 'Category deleted successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
