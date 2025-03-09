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
    

    
    public function addInvoice($invoiceId, $typeInvoice, $cuit, $address, $businessName, $contact, $userId) {
        $stmt = $this->conn->prepare("INSERT INTO invoice (invoice_id, type_invoice, cuit, address, business_name, contact, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isissii", $invoiceId, $typeInvoice, $cuit, $address, $businessName, $contact, $userId);
        $stmt->execute();
        $stmt->close();
    }
    
}

?>