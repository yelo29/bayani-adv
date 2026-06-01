<?php

/**
 * Abstract User Class
 * Abstraction: Defines structure for all user types
 */
abstract class User {
    protected $id;
    protected $email;
    protected $password;
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    abstract public function login($email, $password);
    abstract public function logout();
    abstract public function checkAuth();

    protected function setEmail($email) {
        $this->email = $email;
    }

    protected function setPassword($password) {
        $this->password = $password;
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    protected function validatePassword($password) {
        return strlen($password) >= 6;
    }
}
