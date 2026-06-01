<?php

require_once 'Product.php';

/**
 * PantryProduct Class
 * Inheritance: Extends Product class
 * Polymorphism: Implements getCategoryLabel for pantry items
 */
class PantryProduct extends Product {
    public function getCategoryLabel() {
        return "Pantry Items";
    }
}
