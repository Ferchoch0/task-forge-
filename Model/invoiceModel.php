<?php

class InvoiceModel{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

public function getUserInvoice($userId) {
        $sql = "SELECT clients.name, clients.cuit,
                clients.address, clients.contact, clients.invoice_type, invoice.fech
                FROM invoice
                INNER JOIN clients ON invoice.client_id = clients.client_id
                WHERE invoice.user_id = ? ORDER BY invoice.fech DESC";
       $stmt = $this->conn->prepare($sql);
       if ($stmt === false) {
           die('Prepare failed: ' . htmlspecialchars($this->conn->error));
       }
       
       $stmt->bind_param("i", $userId);
       $stmt->execute();
       
       $result = $stmt->get_result();
       if ($result === false) {
           die('Get result failed: ' . htmlspecialchars($stmt->error));
       }
       return $result->fetch_all(MYSQLI_ASSOC);
   }
    

    
    public function addInvoice($clientId, $userId) {
        $stmt = $this->conn->prepare("INSERT INTO invoice (fech, client_id, user_id) VALUES (NOW(), ?, ?)");
        $stmt->bind_param("ii", $clientId, $userId);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function addDebt($debtType, $debtAmount, $clientId) {
        $stmt = $this->conn->prepare("INSERT INTO debts (debt_type, amount, fech, client_id) VALUES (?, ?, NOW(), ?)");
        $stmt->bind_param("iii", $debtType, $debtAmount, $clientId);
        $stmt->execute();
        $stmt->close();
        return true;
    }

}

?>