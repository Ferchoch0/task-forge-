<?php

class BusinessModel{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCategories(){
        $stmt = $this->conn->prepare("SELECT * FROM categories");
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $categories;
    }

    public function addBusiness($name, $userId, $categoryId) {
        $sql = "INSERT INTO businesses (name, created, user_id, category_id) VALUES (?, NOW(), ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $name, $userId, $categoryId);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }

    public function addLocation($name, $fech, $businessId) {
        $sql = "INSERT INTO locals (name, address, business_id) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $name, $fech, $businessId);
        return $stmt->execute();
    }

    public function getPlans(){
        $stmt = $this->conn->prepare("SELECT * FROM plans");
        $stmt->execute();
        $result = $stmt->get_result();
        $plans = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $plans;
    }

    public function addSubscription($status, $businessId, $planId) {
        $sql = "INSERT INTO subscriptions (status, renewal_date, business_id, plan_id) VALUES (?, NOW(), ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $status, $businessId, $planId);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getBusiness($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM businesses WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $businesses = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $businesses;
    }

    public function updateSubscriptionStatus($businessId) {
        $sql = "UPDATE subscriptions SET status = 'active' WHERE business_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $businessId);
        return $stmt->execute();
    }

}
?>