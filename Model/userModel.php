<?php

class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        return $stmt->num_rows > 0;
    }

    public function registerUser($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Encripta la contraseña
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        return $stmt->execute();
    }
}

?>