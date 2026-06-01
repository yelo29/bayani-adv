<?php

require_once 'Product.php';

/**
 * HomeProduct Class
 * Inheritance: Extends Product class
 * Polymorphism: Implements getCategoryLabel for home goods
 */
class HomeProduct extends Product {
    public function getCategoryLabel() {
        return "Home Goods";
    }
}
