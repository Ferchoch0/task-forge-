<?php

class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    public function registerUser($username, $email, $password) {
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}

?>