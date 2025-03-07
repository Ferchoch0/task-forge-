<?php

class ClientModel{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    
addInvoice($invoice_id, $typeInvoice, $client, $cuit, $address, $businessName) {
    $stmt = $this->conn->prepare("INSERT INTO invoice (invoice_id, typeInvoice, client, cuit, address, businessName) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $invoice_id, $typeInvoice, $client, $cuit, $address, $businessName);
    $stmt->execute();
    $stmt->close();
}
    
}

?>