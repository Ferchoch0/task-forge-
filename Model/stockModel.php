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
    $sql = "SELECT sell.sell_id, stock.products, sell.amount, stock.type_amount, sell.price_sell, sell.payment, sell.fech 
            FROM sell
            INNER JOIN stock ON sell.stock_id = stock.stock_id
            WHERE sell.user_id = ? ORDER BY sell.fech DESC";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

public function addSale($userId, $stockId, $amount, $priceSell, $payment) {
    $this->conn->begin_transaction(); // Iniciar transacción

    $stmt = $this->conn->prepare("INSERT INTO sell (stock_id, amount, price_sell, payment, fech, user_id) VALUES (?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("iidsi", $stockId, $amount, $priceSell, $payment, $userId);
    $stmt->execute();
    $stmt->close();

    $stmt = $this->conn->prepare("UPDATE stock SET stock = stock - ? WHERE stock_id = ? AND user_id = ?");
    $stmt->bind_param("iii", $amount, $stockId, $userId);
    $stmt->execute();
    $stmt->close();

    $this->conn->commit(); // Confirmar transacción
    return true;
}



public function getUserBuys($userId) {
    $sql = "SELECT buy.buy_id, stock.products, buy.amount, stock.type_amount, buy.price_buy, buy.payment, buy.fech 
            FROM buy
            INNER JOIN stock ON buy.stock_id = stock.stock_id
            WHERE buy.user_id = ? ORDER BY buy.fech DESC";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

public function addBuy($userId, $stockId, $amount, $priceBuy, $payment) {
    $this->conn->begin_transaction(); // Iniciar transacción

    $stmt = $this->conn->prepare("INSERT INTO buy (stock_id, amount, price_buy, payment, fech, user_id) VALUES (?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("iidsi", $stockId, $amount, $priceBuy, $payment, $userId);
    $stmt->execute();
    $stmt->close();

    $stmt = $this->conn->prepare("UPDATE stock SET stock = stock + ? WHERE stock_id = ? AND user_id = ?");
    $stmt->bind_param("iii", $amount, $stockId, $userId);
    $stmt->execute();
    $stmt->close();

    $this->conn->commit(); // Confirmar transacción
    return true;
}



public function getUserTransaction($userId) {
    $sql = "SELECT 'sell' AS source, sell.sell_id, stock.products, sell.amount,  sell.price_sell AS price, sell.payment, sell.fech AS date
            FROM sell
            INNER JOIN stock ON sell.stock_id = stock.stock_id
            WHERE sell.user_id = ?
            UNION
            SELECT 'buy' AS source, buy.buy_id, stock.products, buy.amount,  buy.price_buy AS price, buy.payment, buy.fech AS date
            FROM buy
            INNER JOIN stock ON buy.stock_id = stock.stock_id
            WHERE buy.user_id = ?
            ORDER BY date DESC
        ";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $userId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

}
?>