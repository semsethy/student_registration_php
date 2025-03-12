<?php
class Database {
    private $host = "localhost";
    private $db_name = "backend";
    private $username = "root";
    private $password = "root";
    private $conn;

    // Get the PDO connection
    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $exception) {
                echo "Connection error: " . $exception->getMessage();
            }
        }
        return $this->conn;
    }
}
?>
