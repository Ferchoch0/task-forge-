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
    $amount = intval($_POST['amount']);
    $priceStock = floatval($_POST['priceBuy']);
    $payment = $_POST['payment'];

    if ($stockModel->addBuy($userId, $stockId, $amount, $priceStock, $payment)) {
        header("Location: ../View/buy.php?success=1");
    } else {
        header("Location: ../View/buy.php?error=stock");
    }
    exit();
}
?>

