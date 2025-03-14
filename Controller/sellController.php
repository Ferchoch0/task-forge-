<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/stockModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

$stockModel = new StockModel($conn);
$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stockId = $_POST['product_id'];
    $products = $_POST['products'];
    $payment = $_POST['payment'];
    if (isset($_POST['invoice']) && $_POST['invoice'] === 'on') {
        $invoice = $_POST['business_name'];
    } else {
        $invoice = NULL;
    }

    foreach ($products as $product) {
        list($productId, $amount, $price) = explode('|', $product);
        $stockModel->addSale($userId, $productId, $amount, $price, $payment, $invoice);
    }
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}   
?>

