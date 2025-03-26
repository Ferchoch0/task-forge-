<?php

class ClientModel{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserClient($userId){
        $stmt = $this->conn->prepare("SELECT * FROM clients WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addClient($name, $cuit, $contact, $typeInvoice, $address, $userId){
        $stmt = $this->conn->prepare("INSERT INTO clients (name, cuit, contact, invoice_type, address, user_id) VALUES ( ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siissi", $name, $cuit, $contact, $typeInvoice, $address, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function getClientDebt($clientId){
        $stmt = $this->conn->prepare("SELECT * FROM debts WHERE client_id = ?");
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteClient($clientId){
        $stmt = $this->conn->prepare("DELETE FROM clients WHERE client_id = ?");
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $stmt->close();
    }

    
}

?>