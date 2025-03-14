<?php

class ClientModel{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function getUserInvoice($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM invoice WHERE user_id = ? ORDER BY business_name ASC"  );
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    

    
    public function addInvoice($fech, $clientId, $userId) {
        $stmt = $this->conn->prepare("INSERT INTO invoice (fech, client_id, user_id) VALUES (NOW(), ?, ?)");
        $stmt->bind_param("iii", $clientId, $userId);
        $stmt->execute();
        $stmt->close();

    }

    public function getUserClient($userId){
        $stmt = $this->conn->prepare("SELECT * FROM clients WHERE user_id = ? ORDER BY name ASC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addClient($name, $cuit, $contact, $invoiceType, $address, $userId){
        $stmt = $this->conn->prepare("INSERT INTO clients (name, cuit, contact, invoice_type, address, user_id) VALUES ( ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siissi", $name, $cuit, $contact, $invoiceType, $address, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function chargeClient($debtTotal, $debtPaid, $clientId){
        $stmt = $this->conn->prepare("UPDATE clients SET debt_total = ?, debt_paid = ? WHERE client_id = ?");
        $stmt->bind_param("iii", $debtTotal, $debtPaid, $clientId);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteClient($clientId){
        $stmt = $this->conn->prepare("DELETE FROM clients WHERE client_id = ?");
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $stmt->close();
    }
    
}

?>