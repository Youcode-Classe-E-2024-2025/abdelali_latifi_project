<?php

class Database {
    private $host = "localhost";
    private $db_name = "project";
    private $username = "root";
    private $password = "";
    private $connexion;

    public function getConnection() {
        $this->connexion = null;

        try {
            $this->connexion = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage() . "<br>";
        }

        return $this->connexion;
    }
}


