<?php
session_start();
require_once '../Model/clientModel.php';
require_once '../Model/invoiceModel.php';
require_once '../Model/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

header('Content-Type: application/json');


$invoiceModel = new InvoiceModel($conn);
$clientModel = new clientModel($conn);
$userId = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = isset($data['action']) ? $data['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

    switch($action){
        case 'addInvoice':
            if(isset($_POST['cuit'], $_POST['address'], $_POST['businessName'], $_POST['contact'], $_POST['typeInvoice'])){
                $cuit = $_POST['cuit'];
                $address = $_POST['address'];
                $businessName = $_POST['businessName'];
                $contact = $_POST['contact'];
                $typeInvoice = $_POST['typeInvoice'];
        
        
                if ($clientModel->addInvoice($cuit, $address, $businessName, $contact, $userId, $typeInvoice)) {
                     "Producto agregado correctamente.";
                } else {
                    echo "Error al agregar producto.";
                }
            }
            break;

        case 'check-in':
            if (isset($data['id'])) {
                $invoiceId = $data['id'];
                $checkIn = ($data['check'] == 0) ? 1 : 0;
    
                if ($invoiceModel->updateCheck($invoiceId, $checkIn)) {
                    echo json_encode([
                        "status" => "success",
                        "message" => "Estado de factura editada con exito"
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Error al editar estado de Factura"
                    ]);
                }
            }
            break;

        case 'delete':
            if (isset($data['id'])) {
                $invoiceId = $data['id'];
    
                if ($invoiceModel->deleteInvoice($invoiceId)) {
                    echo json_encode([
                        "status" => "success",
                        "message" => "Factura eliminada con exito"
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Error al eliminar Factura"
                    ]);
                }
            }
            break;

        default:
            throw new Exception("Acción no válida");
    }
    
}

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $action = $_GET['action'];

    switch($action) {
        case 'getTable':
            $invoices = $invoiceModel->getUserInvoice($userId);
            if ($invoices) {
                foreach ($invoices as $invoice) {
                    $checkIn = ($invoice['check_in'] == 0) ? 'No Facturada' : 'Facturada';
                    $class = ($invoice['check_in'] == 0) ? 'danger' : 'success';
                    echo "<tr>";
                    echo "<td>{$invoice['cuit']}</td>";
                    echo "<td>{$invoice['address']}</td>" ;
                    echo "<td>{$invoice['name']}</td>" ;
                    echo "<td>{$invoice['contact']}</td>" ;
                    echo "<td>{$invoice['invoice_type']}</td>";
                    echo "<td>{$invoice['fech']}</td>";
                    echo "<td>
                                {$checkIn} 
                                
                          </td>";
                    echo "<td>
                            <div class='table--buttons'>
                                <button class='table--button $class check-in-button' data-id='{$invoice['invoice_id']}' data-check='{$invoice['check_in']}'>
                                    <span class='check-in'></span>
                                </button>
                                <button class='table--button delete-button' data-id='{$invoice['invoice_id']}'>
                                    <span class='delete'></span>
                                </button>
                            </div>
                          </td>";
                    echo "</tr>"; 
                }
            } else {
                echo "<tr><td colspan='6'>No hay productos registrados</td></tr>";
            }
            break;

            default:
            throw new Exception("Acción no válida");
    }
    
}


?>