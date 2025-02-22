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
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, verification_code, verified) VALUES (?, ?, ?, ?, 0)");
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $verificationCode = rand(100000, 999999);
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $verificationCode);
        $result = $stmt->execute();
        $stmt->close();
        return $result ? $verificationCode : false;
    }

    public function verifyUser($userId, $verificationCode) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE user_id = ? AND verification_code = ?");
        $stmt->bind_param("is", $userId, $verificationCode);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $stmt = $this->conn->prepare("UPDATE users SET verified = 1 WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();
            return true;
        } else {
            return false;
        }
    }


    public function isEmailVerified($userId) {
        $stmt = $this->conn->prepare("SELECT verified FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($verified);
        $stmt->fetch();
        $stmt->close();
        return $verified == 1;
    }
    
    public function getUserIdByEmail($email) {
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($userId);
        $stmt->fetch();
        $stmt->close();
        return $userId;
    }


    public function getUserProfileImage($userId) {
        $stmt = $this->conn->prepare("SELECT profile_image FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($profileImage);
        $stmt->fetch();
        $stmt->close();
        return $profileImage;
    }
    
    public function updateUserProfileImage($userId, $imageName) {
        $stmt = $this->conn->prepare("UPDATE users SET profile_image = ? WHERE user_id = ?");
        $stmt->bind_param("si", $imageName, $userId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteUserAccount($userId) {
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
}



?>