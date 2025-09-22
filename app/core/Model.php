<?php

class Model {
    protected $db;

    public function __construct() {
        // Get the PDO connection from our Database class
        $this->db = Database::getInstance()->getConnection();
    }
}
