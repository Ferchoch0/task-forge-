<?php
session_start();
require_once '../Model/stockModel.php';
require_once '../Model/connection.php';
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productName'], $_POST['productStock'], $_POST['productMinStock'], $_POST['productTypeAmount'], $_POST['productPrice'])) {
    $productName = $_POST['productName'];
    $productStock = $_POST['productStock'];
    $productMinStock = $_POST['productMinStock'];
    $productTypeAmount = $_POST['productTypeAmount'];
    $productPrice = $_POST['productPrice'];

    // Instanciar el modelo y agregar el producto
    $stockModel = new StockModel($conn);
    if ($stockModel->addProduct($productName, $productStock, $productMinStock, $productTypeAmount, $productPrice, $userId)) {
        echo "Producto agregado correctamente.";
    } else {
        echo "Error al agregar producto.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action']) && $data['action'] === 'delete' && isset($data['id'])) {
        $stockId = $data['id'];

        // Instanciar el modelo y eliminar el producto
        $stockModel = new StockModel($conn);
        if ($stockModel->deleteProduct($stockId)) {
            echo "Producto eliminado correctamente.";
        } else {
            echo "Error al eliminar el producto.";
        }
    } else {
        echo "Acción no válida o falta ID para eliminar.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editProductId'] ,$_POST['editProductName'], $_POST['editProductStock'], $_POST['editProductMinStock'], $_POST['editProductTypeAmount'])) {
    $editProductName = $_POST['editProductName'];
    $editProductStock = $_POST['editProductStock'];
    $editProductMinStock = $_POST['editProductMinStock'];
    $editProductTypeAmount = $_POST['editProductTypeAmount'];
    $editProductPrice = $_POST['editProductPrice'];
    $editProductId = $_POST['editProductId'];

    // Instanciar el modelo y editar el producto
    $stockModel = new StockModel($conn);
    if ($stockModel->editProduct($editProductName, $editProductStock, $editProductMinStock, $editProductTypeAmount, $editProductPrice, $editProductId)) {
        echo "Producto editado correctamente.";
    } else {
        echo "Error al editar el producto.";
    }
}



?>
