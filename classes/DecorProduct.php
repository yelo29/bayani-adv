<?php

require_once 'Product.php';

/**
 * DecorProduct Class
 * Inheritance: Extends Product class
 * Polymorphism: Implements getCategoryLabel for decor/handicrafts
 */
class DecorProduct extends Product {
    public function getCategoryLabel() {
        return "Decor / Handicrafts";
    }
}
