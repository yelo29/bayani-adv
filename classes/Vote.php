<?php

/**
 * Vote Class
 * Encapsulation: Manages voting logic
 */
class Vote {
    private $db;
    private $userId;

    public function __construct($db, $userId = null) {
        $this->db = $db;
        $this->userId = $userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function toggle($productId) {
        if (!$this->userId) {
            return ['success' => false, 'error' => 'Authentication required'];
        }

        try {
            $this->db->beginTransaction();

            $existingVote = $this->db->fetchOne(
                "SELECT id FROM votes WHERE product_id = :pid AND user_id = :uid",
                [':pid' => $productId, ':uid' => $this->userId]
            );

            if ($existingVote) {
                // Remove vote
                $this->db->execute(
                    "DELETE FROM votes WHERE product_id = :pid AND user_id = :uid",
                    [':pid' => $productId, ':uid' => $this->userId]
                );
                $this->db->execute(
                    "UPDATE products SET vote_count = vote_count - 1 WHERE id = :pid",
                    [':pid' => $productId]
                );
                $voted = false;
            } else {
                // Add vote
                $this->db->execute(
                    "INSERT INTO votes (product_id, user_id) VALUES (:pid, :uid)",
                    [':pid' => $productId, ':uid' => $this->userId]
                );
                $this->db->execute(
                    "UPDATE products SET vote_count = vote_count + 1 WHERE id = :pid",
                    [':pid' => $productId]
                );
                $voted = true;
            }

            $this->db->commit();

            $voteCount = $this->db->fetchOne(
                "SELECT vote_count FROM products WHERE id = :pid",
                [':pid' => $productId]
            );

            return [
                'success' => true,
                'voted' => $voted,
                'vote_count' => $voteCount['vote_count']
            ];

        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function check($productId) {
        if (!$this->userId) {
            return ['voted' => false];
        }

        $vote = $this->db->fetchOne(
            "SELECT id FROM votes WHERE product_id = :pid AND user_id = :uid",
            [':pid' => $productId, ':uid' => $this->userId]
        );

        return ['voted' => $vote !== false];
    }

    public function getUserVotedProducts() {
        if (!$this->userId) {
            return [];
        }

        $votes = $this->db->fetchAll(
            "SELECT product_id FROM votes WHERE user_id = :uid",
            [':uid' => $this->userId]
        );

        return array_column($votes, 'product_id');
    }
}
