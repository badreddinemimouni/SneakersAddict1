<?php

abstract class Model {
    protected $pdo;
    protected $mysqli;
    
    public function __construct() {
        $this->connectPDO();
        $this->connectMySQLi();
    }
    
    private function connectPDO() {
        try {
            require_once __DIR__ . '/../../config/config.php';
            $this->pdo = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD, [
     PDO::MYSQL_ATTR_SSL_CA => true,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Erreur de connexion PDO : " . $e->getMessage());
        }
    }
    
    private function connectMySQLi() {
        try {
            require_once __DIR__ . '/../../config/config.php';
            $this->mysqli = new mysqli();
            $this->mysqli->ssl_set(null, null, null, null, null);
            $this->mysqli->real_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, null, MYSQLI_CLIENT_SSL);
            if ($this->mysqli->connect_error) {
                throw new Exception("Erreur de connexion MySQLi : " . $this->mysqli->connect_error);
            }
            $this->mysqli->set_charset("utf8");
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
    
    protected function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    protected function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    protected function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 