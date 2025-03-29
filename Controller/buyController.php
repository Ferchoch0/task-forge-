<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/stockModel.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

header('Content-Type: application/json');


$stockModel = new StockModel($conn);
$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST['action'];

    switch($action) {
        case 'addBuy':
            try{
                if (isset($_POST['product_id'], $_POST['products'], $_POST['payment'])) {
                    $stockId = $_POST['product_id'];
                    $products = $_POST['products'];
                    $payment = $_POST['payment'];
                    $supplier = $_POST['supplier'];
            
                
                    foreach ($products as $product) {
                        list($productId, $amount, $price) = explode('|', $product);
                        $stockModel->addBuy($userId, $stockId, $amount, $price, $payment, $supplier);
                    }
            
                    echo json_encode([
                        "status" => "success",
                        "message" => "Compra registrada exitosamente."
                    ]);
                } else  {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Datos incompletos para procesar la compra."
                    ]);
                    exit;
                }
            }catch (Exception $e) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al registrar la venta: " . $e->getMessage()
                ]);
            }
            break;

            default:
            throw new Exception("Acción no válida");
    }
}


if ($_SERVER["REQUEST_METHOD"] === "GET"){

    $action = $_GET['action'];

    switch($action) {
        case 'getTable':
            $buys = $stockModel->getUserBuys($userId);

            if ($buys) {
                foreach ($buys as $buy) {
                    echo "<tr>";
                    echo "<td>{$buy['products']}</td>";
                    echo "<td>{$buy['amount']} {$buy['type_amount']}</td>" ;
                    echo "<td>$" . number_format($buy['price_buy'], 2) . "</td>";
                    echo "<td>{$buy['payment']}</td>";
                    echo "<td>{$buy['name']}</td>";
                    echo "<td>$" . number_format($buy['amount'] * $buy['price_buy'],2) . "</td>";
                    echo "<td>{$buy['fech']}</td>";
                    echo "</tr>"; 
                }
            } else {
                echo "<tr><td colspan='6'>No hay productos registrados</td></tr>";
            }
            break;

        case 'getSupplier':
            $suppliers = $stockModel->getUserSupplier($userId);
            foreach ($suppliers as $supplier) {
                echo "<option value='{$supplier['supplier_id']}'>{$supplier['name']}</option>";
            }
            break;

            default:
            throw new Exception("Acción no válida");
        }
    
}
?>

