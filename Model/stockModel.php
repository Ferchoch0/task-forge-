<?php

class StockModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


public function getUserProducts($userId) {
    $stmt = $this->conn->prepare("SELECT * FROM stock WHERE user_id = ? ORDER BY products ASC"  );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_all(MYSQLI_ASSOC);
}

public function addProduct($name, $stock, $min_stock, $type_amount, $price, $user_id) {
    $sql = "INSERT INTO stock (products, stock, stock_min, type_amount, price, user_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("siisii", $name, $stock, $min_stock, $type_amount, $price, $user_id);
    return $stmt->execute();
}

public function deleteProduct($stockId) {
    $sql = "DELETE FROM stock WHERE stock_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $stockId);
    return $stmt->execute();
}

public function editProduct($name, $stock, $min_stock, $type_amount, $price, $stockId) {
    $sql = "UPDATE stock SET products = ?, stock = ?, stock_min = ?, type_amount = ?, price = ? WHERE stock_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("siisii", $name, $stock, $min_stock, $type_amount, $price, $stockId);
    return $stmt->execute();
}

public function getUserSells($userId) {
    $sql = "SELECT sell.sell_id, stock.products, sell.amount, stock.type_amount, sell.price_sell, sell.payment, clients.invoice_type, sell.fech
            FROM sell
            INNER JOIN stock ON sell.stock_id = stock.stock_id
            LEFT JOIN clients ON sell.client_id = clients.client_id
            WHERE sell.user_id = ? ORDER BY sell.fech DESC";
    
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

public function addSale($userId, $stockId, $amount, $priceSell, $payment, $invoiceId) {
    $this->conn->begin_transaction(); // Iniciar transacción

    $stmt = $this->conn->prepare("INSERT INTO sell (stock_id, amount, price_sell, payment, fech, user_id, client_id) VALUES (?, ?, ?, ?, NOW(), ?, ?)");
    $stmt->bind_param("iidsii", $stockId, $amount, $priceSell, $payment, $userId, $invoiceId);
    $stmt->execute();
    $stmt->close();

    $stmt = $this->conn->prepare("UPDATE stock SET stock = stock - ? WHERE stock_id = ? AND user_id = ?");
    $stmt->bind_param("iii", $amount, $stockId, $userId);
    $stmt->execute();
    $stmt->close();

    $this->conn->commit(); // Confirmar transacción
    return true;
}

public function deleteSell($sellId) {
    $sql = "DELETE FROM sell WHERE sell_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $sellId);
    return $stmt->execute();
}




public function getUserBuys($userId) {
    $sql = "SELECT buy.buy_id, stock.products, buy.amount, stock.type_amount, buy.price_buy, buy.payment, supplier.name ,buy.fech 
            FROM buy
            INNER JOIN stock ON buy.stock_id = stock.stock_id
            LEFT JOIN supplier ON buy.supplier_id = supplier.supplier_id
            WHERE buy.user_id = ? ORDER BY buy.fech DESC";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

public function addBuy($userId, $stockId, $amount, $priceBuy, $payment, $supplierId) {
    $this->conn->begin_transaction(); // Iniciar transacción

    $stmt = $this->conn->prepare("INSERT INTO buy (stock_id, amount, price_buy, payment, fech, user_id, supplier_id) VALUES (?, ?, ?, ?, NOW(), ?, ?)");
    $stmt->bind_param("iidsii", $stockId, $amount, $priceBuy, $payment, $userId, $supplierId);
    $stmt->execute();
    $stmt->close();

    $stmt = $this->conn->prepare("UPDATE stock SET stock = stock + ? WHERE stock_id = ? AND user_id = ?");
    $stmt->bind_param("iii", $amount, $stockId, $userId);
    $stmt->execute();
    $stmt->close();

    $this->conn->commit(); // Confirmar transacción
    return true;
}

public function deleteBuy($buyId) {
    $sql = "DELETE FROM buy WHERE buy_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $buyId);
    return $stmt->execute();
}

public function addQuantityStock($stockId) {
    $this->conn->begin_transaction();

    $stmt = $this->conn->prepare("UPDATE stock SET quantity = quantity + 1 WHERE stock_id = ?");
    $stmt->bind_param("i", $stockId);
    $stmt->execute();
    $stmt->close();

    $this->conn->commit();
    return true;
}

public function addQuantityClient($clientId) {
    $this->conn->begin_transaction();

    $stmt = $this->conn->prepare("UPDATE clients SET quantity = quantity + 1 WHERE client_id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $stmt->close();


    $this->conn->commit();
    return true;
}

public function getUserSupplier($userId) {
    $stmt = $this->conn->prepare("SELECT * FROM supplier WHERE user_id = ? ORDER BY name ASC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_all(MYSQLI_ASSOC);
}

public function addSupplier($name, $contact, $userId) {
    $stmt = $this->conn->prepare("INSERT INTO supplier (name, contact, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $name, $contact, $userId);
    $stmt->execute();
    $stmt->close();

    return true;
}

}
?>