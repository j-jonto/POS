<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dbFile = ROOT_PATH . '/app/database/database.sqlite';
        $dsn = 'sqlite:' . $dbFile;

        try {
            $this->pdo = new PDO($dsn);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // In a real app, you'd want to log this error, not just echo it
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
