<?php
session_start();
require_once '../Model/clientModel.php';
require_once '../Model/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

$clientModel = new clientModel($conn);
$userId = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cuit = $_POST['cuit'];
    $address = $_POST['address'];
    $businessName = $_POST['businessName'];
    $contact = $_POST['contact'];
    $typeInvoice = $_POST['typeInvoice'];


    // Instanciar el modelo y agregar el producto
    if ($clientModel->addInvoice($cuit, $address, $businessName, $contact, $userId, $typeInvoice)) {
        echo "Producto agregado correctamente.";
    } else {
        echo "Error al agregar producto.";
    }

    exit();
}


?>