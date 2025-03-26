<?php 

class BalanceModel{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserBalance($userId){
        $stmt = $this->conn->prepare("SELECT * FROM cash WHERE user_id = ? ORDER BY fech DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addBalance($description, $movType, $amount, $payment, $userId){
        $stmt = $this->conn->prepare("INSERT INTO cash (description, mov_type, cash_amount, payment, fech, user_id) VALUES (?, ?, ?, ?, NOW(), ?)");
        $stmt->bind_param("siisi", $description, $movType, $amount, $payment, $userId);
        return $stmt->execute();
    }

    


    public function getUserTransaction($userId) {
        $sql = "SELECT 'sell' AS source, sell.sell_id, stock.products, sell.amount, sell.price_sell AS price, sell.payment, sell.fech AS date, NULL AS mov_type
                FROM sell
                INNER JOIN stock ON sell.stock_id = stock.stock_id
                WHERE sell.user_id = ?

                UNION

                SELECT 'buy' AS source, buy.buy_id, stock.products, buy.amount, buy.price_buy AS price, buy.payment, buy.fech AS date, NULL AS mov_type
                FROM buy
                INNER JOIN stock ON buy.stock_id = stock.stock_id
                WHERE buy.user_id = ?

                UNION 

                SELECT 'cash' AS source, cash_id, description AS products, NULL AS amount, cash_amount AS price, payment, fech AS date, mov_type
                FROM cash
                WHERE cash.user_id = ?

                ORDER BY date DESC;";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $userId, $userId);
        $stmt->execute();
    
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSalesHourToday($userId) {
        // Obtener ventas por hora
        $sql = "SELECT HOUR(fech) as hora, 
                       amount * price_sell as total_venta
                FROM sell
                WHERE DATE(fech) = CURDATE() AND user_id = ? 
                ORDER BY HOUR(fech)";  // Ordena por la hora para asegurar que estén en el orden correcto
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ventas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        return $ventas;
    }
    


    public function getBalanceToday($userId) {
        // Obtener ventas
        $sql = "SELECT DATE(fech) as fecha, 
                         SUM(amount * price_sell) as total 
                  FROM sell 
                  WHERE DATE(fech) = CURDATE() AND user_id = ?
                  GROUP BY DATE(fech)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ventas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        // Obtener compras
        $sql = "SELECT DATE(fech) as fecha, 
                         SUM(amount * price_buy) as total 
                  FROM buy 
                  WHERE DATE(fech) = CURDATE() AND user_id = ?
                  GROUP BY DATE(fech)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $compras = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        return ['ventas' => $ventas, 'compras' => $compras];
    }

    public function getBalanceWeek($userId){
        // Obtener ventas
        $sql = "SELECT DATE(fech) as fecha, 
                         SUM(amount * price_sell) as total 
                  FROM sell 
                  WHERE YEARWEEK(fech) = YEARWEEK(CURDATE()) AND user_id = ? 
                  GROUP BY DATE(fech)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ventas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        // Obtener compras
        $sql = "SELECT DATE(fech) as fecha, 
                         SUM(amount * price_buy) as total 
                  FROM buy 
                  WHERE YEAR(fech) = YEAR(CURDATE()) AND WEEK(fech, 1) = WEEK(CURDATE(), 1) AND user_id = ?
                  GROUP BY DATE(fech)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $compras = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        return ['ventas' => $ventas, 'compras' => $compras];
    }

    public function getSalesTotal($userId) {
        $sql = "SELECT 
                SUM(amount * price_sell) AS total_ventas
                FROM sell
                WHERE user_id = ? AND payment != 'Deuda'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ventas = $result->fetch_assoc();
    
        $stmt->close();
        
        // Si no hay ventas, retorna 0
        return $ventas['total_ventas'] ?? 0;
    }
    

    public function getSalesMonth($userId){
        $sql = "SELECT
                SUM(amount * price_sell) AS total_ventas
                FROM sell
                WHERE user_id = ? AND payment != 'Deuda'  AND MONTH(fech) = MONTH(CURDATE()) AND YEAR(fech) = YEAR(CURDATE())";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ventas = $result->fetch_assoc();
    
        $stmt->close();
        
        // Si no hay ventas, retorna 0
        return $ventas['total_ventas'] ?? 0;
    }

    public function getClientQuantity($userId) {
        $sql = "SELECT name, quantity FROM clients
                WHERE user_id = ? AND quantity > 0
                ORDER BY quantity DESC
                LIMIT 5";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_all(MYSQLI_ASSOC); // Devuelve un array con los 5 clientes
    }

    public function getStockQuantity($userId) {
        $sql = "SELECT products, quantity FROM stock
                WHERE user_id = ? AND quantity > 0
                ORDER BY quantity DESC
                LIMIT 5";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_all(MYSQLI_ASSOC); // Devuelve un array con los 5 productos
    }
    
}


?>