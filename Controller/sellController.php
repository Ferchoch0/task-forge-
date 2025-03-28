<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/stockModel.php';
require_once '../Model/invoiceModel.php';
require_once '../Model/clientModel.php';



if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

header('Content-Type: application/json');

$stockModel = new StockModel($conn);
$invoiceModel = new InvoiceModel($conn);
$clientModel = new ClientModel($conn);

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST['action'];

    switch($action){
        case 'addSell':
            try {
                if (isset($_POST['product_id'], $_POST['products'], $_POST['payment'])) {
                    $stockId = $_POST['product_id'];
                    $products = $_POST['products'];
                    $payment = $_POST['payment'];
                    $debtTotal = 0;
                    $debtType = 1;
                    
                    if (!empty($_POST['invoice']) && $_POST['invoice'] === 'on') {
                        $invoice = $_POST['name'];
                        $invoiceModel->addInvoice($invoice, $userId);
                        $stockModel->addQuantityClient($invoice);
                    } else {
                        $invoice = NULL;
                    }
            
                    foreach ($products as $product) {
                        list($productId, $amount, $price) = explode('|', $product);
            
                        if ($payment === 'deuda') {
                            $debtTotal += $amount * $price;
                        }
            
                        $stockModel->addSale($userId, $productId, $amount, $price, $payment, $invoice);
                    }
            
                    $stockModel->addQuantityStock($stockId);
            
                    if ($payment === 'deuda') {
                        $invoiceModel->addDebt($debtType, $debtTotal, $invoice);
                    }
            
                    echo json_encode([
                        "status" => "success",
                        "message" => "Venta registrada correctamente."
                    ]);
                }
            } catch (Exception $e) {
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

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $action = $_GET['action'];

    switch($action){
        case 'getTable':
            $sells = $stockModel->getUserSells($userId);
        
            if ($sells) {
                foreach ($sells as $sell) {
                    $invoice = $sell['invoice_type'] ? $sell['invoice_type'] : "No"; 
                    echo "<tr>";
                    echo "<td>{$sell['products']}</td>";
                    echo "<td>{$sell['amount']} {$sell['type_amount']}</td>";
                    echo "<td>$" . number_format($sell['price_sell'], 2) . "</td>";
                    echo "<td>{$sell['payment']}</td>";
                    echo "<td>$" .  number_format($sell['amount'] * $sell['price_sell'], 2) . "</td>";
                    echo "<td>{$invoice}</td>";
                    echo "<td>{$sell['fech']}</td>";
                    echo "</tr>"; 
                    }
            } else {
                echo "<tr><td colspan='7'>No hay productos registrados</td></tr>";
            }
            
            break;

        case 'getClients':
            $clients = $clientModel->getUserClient($userId); // Obtener facturas del usuario

            if ($clients) {
                foreach($clients as $client) {
                    echo "<option value='{$client['client_id']}'> {$client['name']} </option>";
                }
            } else {
                echo "<option value=''>No hay clientes registrados</option>";
            }
        break;
        
        default:
        throw new Exception("Acción no válida");
    }
    
}
?>

