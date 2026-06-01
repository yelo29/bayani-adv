<?php

require_once 'Product.php';

/**
 * ApparelProduct Class
 * Inheritance: Extends Product class
 * Polymorphism: Implements getCategoryLabel for apparel
 */
class ApparelProduct extends Product {
    public function getCategoryLabel() {
        return "Apparel";
    }
}
