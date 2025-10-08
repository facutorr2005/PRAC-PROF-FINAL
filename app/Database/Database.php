<?php

namespace App\Database;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $this->connection = new \PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS
            );
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_CLASS);
            $this->connection->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
        } catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            die();
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
