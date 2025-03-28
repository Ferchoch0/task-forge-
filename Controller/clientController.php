<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/clientModel.php';
require_once '../Model/invoiceModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

header('Content-Type: application/json');

$clientModel = new ClientModel($conn);
$invoiceModel = new InvoiceModel($conn);
$userId = $_SESSION['user_id'];



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Agregar cliente
    if (isset($_POST['name']) && isset($_POST['cuit']) && isset($_POST['address']) && isset($_POST['contact']) && isset($_POST['typeInvoice'])) {
        $name = $_POST['name'];
        $cuit = $_POST['cuit'];
        $contact = $_POST['contact'];
        $typeInvoice = $_POST['typeInvoice'];
        $address = $_POST['address'];

        $result = $clientModel->addClient($name, $cuit, $contact, $typeInvoice, $address, $userId);

        if($result){
            echo json_encode([
                "status" => "success",
                "message" => "Cliente agregado con exito"
            ]);
        }else {
        echo json_encode([
            "status" => "error",
            "message" => "Error al agregar Cliente"
        ]);
    }    
    exit();

    } else if (isset($_POST['chargeClientId']) && isset($_POST['clientDebtTotal']) && isset($_POST['clientDebtPaid'])) {
        $chargeClientId = $_POST['chargeClientId'];
        $debtPaid = $_POST['clientDebtPaid'];
        $debtType = 0;

        if ($invoiceModel->addDebt($debtType, $debtPaid, $chargeClientId)) {
            echo json_encode([
                "status" => "success",
                "message" => "Deuda cobrada con exito"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al cobrar deuda"
            ]);
        }
        exit();
    } else if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action']) && $data['action'] === 'delete' && isset($data['id'])) {
            $clientId = $data['id'];

            if ($clientModel->deleteClient($clientId)) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Cliente eliminado con exito"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al eliminar cliente"
                ]);
            }
        exit();
        }
    }

} 

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    if (isset($_GET['client_id'])) {
        $clientId = $_GET['client_id'];
        $debts = $clientModel->getClientDebt($clientId);
        
        // Retornar la deuda en formato JSON
        echo json_encode($debts);
        exit();
    }

    if($_GET['action'] === 'getTable'){
        $clients = $clientModel->getUserClient($userId);

        if ($clients) {
            foreach ($clients as $client) {
                $debts = $clientModel->getClientDebt($client['client_id']);
                $debt_total = 0;
                foreach ($debts as $debt) {
                    if ($debt['debt_type'] === 0) {
                        $debt_total -= $debt['amount'];
                    } else {
                    $debt_total += $debt['amount']; }
    
    
                }
                echo "<tr>";
                echo "<td>{$client['name']}</td>";
                echo "<td>$" . number_format($debt_total, 2) . "</td>";
                echo "<td>{$client['contact']}</td>";
                echo "<td>
                        <div class='table--buttons'>
                            <button class='table--button delete-button' data-id='{$client['client_id']}'>
                                <span class='delete'></span>
                            </button>
                            <button class='table--button charge-button' data-id='{$client['client_id']}' data-debt='{$debt_total}''>
                                <span class='edit'></span>
                            </button>
                            <button class='table--button history-button' data-id='{$client['client_id']}' data-id='{$debt['client_id']}'>
                                <span class='history'></span>
                            </button>
                        </div>
                    </td>";
                echo "</tr>"; 
            }
        } else {
            echo "<tr><td colspan='4'>No hay clientes registrados</td></tr>";
        }
    }
    
}




?>