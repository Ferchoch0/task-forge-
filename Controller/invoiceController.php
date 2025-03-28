<?php
session_start();
require_once '../Model/clientModel.php';
require_once '../Model/invoiceModel.php';
require_once '../Model/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}


$invoiceModel = new InvoiceModel($conn);
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

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if($_GET['action'] === 'getTable'){
        $invoices = $invoiceModel->getUserInvoice($userId);
        if ($invoices) {
            foreach ($invoices as $invoice) {
                echo "<tr>";
                echo "<td>{$invoice['cuit']}</td>";
                echo "<td>{$invoice['address']}</td>" ;
                echo "<td>{$invoice['name']}</td>" ;
                echo "<td>{$invoice['contact']}</td>" ;
                echo "<td>{$invoice['invoice_type']}</td>";
                echo "<td>{$invoice['fech']}</td>";
                echo "</tr>"; 
            }
        } else {
            echo "<tr><td colspan='6'>No hay productos registrados</td></tr>";
        }
    }
    
}


?>