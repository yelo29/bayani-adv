<?php

/**
 * Abstract Product Class
 * Abstraction: Defines structure for all product types
 */
abstract class Product {
    protected $id;
    protected $title;
    protected $origin;
    protected $tag;
    protected $tagClass;
    protected $description;
    protected $imageUrl;
    protected $sector;
    protected $voteCount;
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    abstract public function getCategoryLabel();

    public function load($id) {
        $product = $this->db->fetchOne(
            "SELECT * FROM products WHERE id = :id",
            [':id' => $id]
        );

        if ($product) {
            $this->id = $product['id'];
            $this->title = $product['title'];
            $this->origin = $product['origin'];
            $this->tag = $product['tag'];
            $this->tagClass = $product['tag_class'];
            $this->description = $product['description'];
            $this->imageUrl = $product['image_url'];
            $this->sector = $product['sector'];
            $this->voteCount = $product['vote_count'];
            return true;
        }
        return false;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'origin' => $this->origin,
            'tag' => $this->tag,
            'tag_class' => $this->tagClass,
            'description' => $this->description,
            'image_url' => $this->imageUrl,
            'sector' => $this->sector,
            'vote_count' => $this->voteCount
        ];
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getVoteCount() {
        return $this->voteCount;
    }

    protected function validate() {
        return !empty($this->title) && !empty($this->origin) && 
               !empty($this->tag) && !empty($this->tagClass) && 
               !empty($this->sector) && !empty($this->description);
    }
}
