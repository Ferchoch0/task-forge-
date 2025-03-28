<?php
session_start();
require_once '../Model/stockModel.php';
require_once '../Model/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

header('Content-Type: application/json');


$userId = $_SESSION['user_id'];
$stockModel = new StockModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productName'], $_POST['productStock'], $_POST['productMinStock'], $_POST['productTypeAmount'], $_POST['productPrice'])) {
    $productName = $_POST['productName'];
    $productStock = $_POST['productStock'];
    $productMinStock = $_POST['productMinStock'];
    $productTypeAmount = $_POST['productTypeAmount'];
    $productPrice = $_POST['productPrice'];

    if ($stockModel->addProduct($productName, $productStock, $productMinStock, $productTypeAmount, $productPrice, $userId)) {
        echo json_encode([
            "status" => "success",
            "message" => "Producto agregado correctamente."
        ]);
        
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error al agregar el producto."
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action']) && $data['action'] === 'delete' && isset($data['id'])) {
        $stockId = $data['id'];


        if ($stockModel->deleteProduct($stockId)) {
            echo json_encode([
                "status" => "success",
                "message" => "Producto eliminado correctamente."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al eliminar el producto."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error en la solicitud."
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editProductId'] ,$_POST['editProductName'], $_POST['editProductStock'], $_POST['editProductMinStock'], $_POST['editProductTypeAmount'])) {
    $editProductName = $_POST['editProductName'];
    $editProductStock = $_POST['editProductStock'];
    $editProductMinStock = $_POST['editProductMinStock'];
    $editProductTypeAmount = $_POST['editProductTypeAmount'];
    $editProductPrice = $_POST['editProductPrice'];
    $editProductId = $_POST['editProductId'];


    if ($stockModel->editProduct($editProductName, $editProductStock, $editProductMinStock, $editProductTypeAmount, $editProductPrice, $editProductId)) {
        echo json_encode([
            "status" => "success",
            "message" => "Producto editado correctamente."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error al editar el producto."
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getTable') {
    $products = $stockModel->getUserProducts($userId);
    $margin = 0.30;
    $iva = 0.21;

    if ($products) {
        foreach ($products as $product) {
            $lowStockClass = ($product['stock'] <= $product['stock_min'] && $product['stock'] != 0) ? 'low-stock' : '';
            $nullStockClass = ($product['stock'] == 0) ? 'null-stock' : '';
            $cost = $product['price'];
            $salePrice = $cost * (1 + $margin) * (1 + $iva);

            echo "<tr class='$lowStockClass $nullStockClass'>
                    <td>{$product['products']}</td>
                    <td>{$product['stock']} {$product['type_amount']}</td>
                    <td>{$product['stock_min']} {$product['type_amount']}</td>
                    <td>$" . number_format($product['price'], 2) . "</td>
                    <td>$" . number_format($salePrice, 2) . "</td>
                    <td>
                        <div class='table--buttons'>
                            <button class='table--button delete-button' data-id='{$product['stock_id']}'>
                                <span class='delete'></span>
                            </button>
                            <button class='table--button edit-button' 
                                data-id='{$product['stock_id']}' 
                                data-name='{$product['products']}' 
                                data-stock='{$product['stock']}' 
                                data-min-stock='{$product['stock_min']}' 
                                data-type-amount='{$product['type_amount']}' 
                                data-price='{$product['price']}'>
                                <span class='edit'></span>
                            </button>
                        </div>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No hay productos registrados</td></tr>";
    }
}


?>
