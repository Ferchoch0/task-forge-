<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../Model/stockModel.php';
require_once '../Model/connection.php';
require_once "../Libs/excelReader.php";
require_once "../Libs/csvReader.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

header('Content-Type: application/json');


$userId = $_SESSION['user_id'];
$stockModel = new StockModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);
    $action = isset($data['action']) ? $data['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

    try {
    switch($action){
        case 'addProduct':
            if (isset($_POST['productName'], $_POST['productStock'], $_POST['productMinStock'], $_POST['productTypeAmount'], $_POST['productPrice'])){
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
            
            break;

            case "uploadFile":
                if (!isset($_FILES["file"])) {
                    echo json_encode([
                        "status" => "error", 
                        "message" => "No se subió ningún archivo."
                    ]);
                    exit;
                }
            
                // Obtener la carpeta temporal personalizada dentro de 'controller/temp'
                $uploadDir = __DIR__ . '/temp/';
            
                // Asegurarse de que la carpeta 'temp' exista
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);  // Crear la carpeta si no existe
                }
            
                // Generar la ruta completa del archivo
                $filePath = $uploadDir . basename($_FILES["file"]["name"]);
            
                // Mover el archivo subido a la carpeta temporal
                if (!move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
                    echo json_encode([
                        "status" => "error", 
                        "message" => "Error al mover el archivo a la carpeta temporal."
                    ]);
                    exit;
                }
            
                // Guardar la ruta del archivo en la sesión
                $_SESSION["uploaded_file"] = $filePath;
            
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            
                // Leer los encabezados según el tipo de archivo
                $columns = [];
                if ($extension === "csv") {
                    $columns = getCSVHeaders($filePath);
                } elseif ($extension === "xlsx") {
                    $columns = getExcelHeaders($filePath);
                }
            
                // Verificar si se pudieron leer los encabezados
                if (empty($columns)) {
                    echo json_encode([
                        "status" => "error", 
                        "message" => "No se pudieron leer los encabezados del archivo."
                    ]);
                } else {
                    echo json_encode([
                        "status" => "success", "columns" => $columns]);
                }
            
                // Eliminar el archivo temporal después de procesarlo
                // if (file_exists($filePath)) {
                //     unlink($filePath);  // Eliminar el archivo temporal
                // }
            
                break;            
            

            case "importData":
                $filePath = $_SESSION["uploaded_file"] ?? null;
            
                if (!$filePath) {
                    echo json_encode(["status" => "error", "message" => "No hay archivo para procesar."]);
                    exit;
                }
            
                $data = [];
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            
                if ($extension === "csv") {
                    $data = readCSVData($filePath);
                } elseif ($extension === "xlsx") {
                    $data = readExcelData($filePath);
                }

                $userId = $_SESSION["user_id"]; // Obtener el user_id de la sesión
            


                foreach ($data as $row) {
                    $product = $row[$_POST["products"]];
                    $stock = !empty($row[$_POST["stock"]]) ? $row[$_POST["stock"]] : 1;
                    $minStock = !empty($row[$_POST["stock_min"]]) ? $row[$_POST["stock_min"]] : 1;
                    $typeAmount = !empty($row[$_POST["type_amount"]]) ? $row[$_POST["type_amount"]] : 'u.';

                    $price = str_replace(['$', ' '], '', $row[$_POST["price"]]); // Elimina el símbolo de $
                    $price = str_replace(['.', ','], ['', '.'], $price); // Elimina puntos y cambia coma por punto
                    $price = (int) round((float) $price);

                    var_dump($row[$_POST["price"]]); // Muestra el valor original del precio
                    var_dump($price); // Muestra el valor convertido
                    die();
                

                    $stockModel->addProduct($product, $stock, $minStock, $typeAmount, $price, $userId);
                }
            
                echo json_encode(["status" => "success", "message" => "Datos importados correctamente."]);
                break;
        
        case 'editProduct':
            if(isset($_POST['editProductId'] ,$_POST['editProductName'], $_POST['editProductStock'], $_POST['editProductMinStock'], $_POST['editProductTypeAmount'])){
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
            break;
        
        case 'delete':
                if (isset($data['id'])) {
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
            break;
        
        default:
            throw new Exception("Acción no válida");
    } 
    }  catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
    exit();

}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = $_GET['action'];
    $products = $stockModel->getUserProducts($userId);

    try{
    switch($action){
        case 'getTable':
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
            break;
        
        case 'lowStock':
            $lowStockCount = 0;
            foreach ($products as $product) {
                if ($product['stock'] <= $product['stock_min'] && $product['stock'] != 0) {
                    $lowStockCount++;
                }
            }

            echo "<p> $lowStockCount productos están por debajo del stock mínimo</p>";
            break;

        case 'nullStock':      
            $nullStockCount = 0;
            foreach ($products as $product) {
                if ($product['stock'] == 0) {
                    $nullStockCount++;
                }
            }

            echo "<p> $nullStockCount productos están sin stock</p>";
            break;


        default:
            throw new Exception("Acción no válida");

    }
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
    exit();
}


?>
