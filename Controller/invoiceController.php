<?php
session_start();
require_once '../Model/clientModel.php';
require_once '../Model/connection.php';
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['typeInvoice'], $_POST['cuit'], $_POST['address'], $_POST['buinessName'], $_POST['contact'])) {
    $typeInvoice = $_POST['typeInvoice'];
    $cuit = $_POST['cuit'];
    $address = $_POST['address'];
    $buinessName = $_POST['buinessName'];
    $contact = $_POST['contact'];

    // Instanciar el modelo y agregar el producto
    $clientModel = new clientModel($conn);
    if ($clientModel->addInvoice($typeInvoice, $cuit, $address, $buinessName, $contact, $userId)) {
        echo "Producto agregado correctamente.";
    } else {
        echo "Error al agregar producto.";
    }
}



?>