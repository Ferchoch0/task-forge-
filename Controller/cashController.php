<?php 
session_start();
require_once '../Model/connection.php';
require_once '../Model/balanceModel.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

ini_set('display_errors', 1);  // Muestra los errores de PHP
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 

$balanceModel = new BalanceModel($conn);
$userId = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Agregar saldo
  if (isset($_POST['description']) && isset($_POST['typeMov']) && isset($_POST['amount']) && isset($_POST['payment'])) {
    $description = $_POST['description'];
    $movType = $_POST['typeMov'];
    $amount = $_POST['amount'];
    $payment = $_POST['payment'];

    if ($balanceModel->addBalance($description, $movType, $amount, $payment, $userId)) {
      header("Location: ../View/cash.php?success=1");
    } else {
      header("Location: ../View/cash.php?error=balance");
    }
    exit();
  }
}


?>