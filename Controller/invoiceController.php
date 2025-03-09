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
    $typeInvoice = $_POST['typeInvoice'];
    $cuit = $_POST['cuit'];
    $address = $_POST['address'];
    $businessName = $_POST['businessName'];
    $contact = $_POST['contact'];

    // Instanciar el modelo y agregar el producto
    if ($clientModel->addInvoice($typeInvoice, $cuit, $address, $businessName, $contact, $userId)) {
        echo "Producto agregado correctamente.";
    } else {
        echo "Error al agregar producto.";
    }
}



?>