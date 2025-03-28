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
    $name = $_POST['supplier'];
    $contact = $_POST['contact'];

    if ($stockModel->addSupplier($name, $contact, $userId)) {
        header("Location: ../View/supplier.php?success=1");
    } else {
        header("Location: ../View/supplier.php?error=stock");
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET"){

    if($_GET['action'] === 'getTable'){
        $suppliers = $stockModel->getUserSupplier($userId);
        if ($suppliers) {
            foreach ($suppliers as $supplier) {
                echo "<tr>";
                echo "<td>{$supplier['name']}</td>";
                echo "<td>{$supplier['contact']}</td>" ;
                echo "</tr>"; 
            }
            } else {
                echo "<tr><td colspan='2'>No hay productos registrados</td></tr>";
            }
    }
    
}
?>

