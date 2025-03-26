<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/stockModel.php';
require_once '../Model/invoiceModel.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

$stockModel = new StockModel($conn);
$invoiceModel = new InvoiceModel($conn);
$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stockId = $_POST['product_id'];
    $products = $_POST['products'];
    $payment = $_POST['payment'];
    $debtTotal = 0;
    $debtType = 1;
    

    if (isset($_POST['invoice']) && $_POST['invoice'] === 'on') {
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

    // Si el pago fue "deuda", agregar la deuda total una sola vez
    if ($payment === 'deuda') {
        $invoiceModel->addDebt($debtType ,$debtTotal, $invoice);
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}   
?>

