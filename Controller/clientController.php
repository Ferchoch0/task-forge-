<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/clientModel.php';
require_once '../Model/invoiceModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

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

        if ($clientModel->addClient($name, $cuit, $contact, $typeInvoice, $address, $userId)) {
            header("Location: ../View/client.php?success=1");
        } else {
            header("Location: ../View/client.php?error=client");
        }
        exit();
    }

    // Cobrar cliente
    if (isset($_POST['chargeClientId']) && isset($_POST['clientDebtTotal']) && isset($_POST['clientDebtPaid'])) {
        $chargeClientId = $_POST['chargeClientId'];
        $debtPaid = $_POST['clientDebtPaid'];
        $debtType = 0;

        if ($invoiceModel->addDebt($debtType, $debtPaid, $chargeClientId)) {
            header("Location: ../View/client.php?success=2");
        } else {
            header("Location: ../View/client.php?error=client");
        }
        exit();
    }

    // Eliminar cliente
    if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action']) && $data['action'] === 'delete' && isset($data['id'])) {
            $clientId = $data['id'];

            if ($clientModel->deleteClient($clientId)) {
                echo "Cliente eliminado correctamente.";
            } else {
                echo "Hubo un problema al eliminar al cliente.";
            }
        exit();
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['client_id'])) {
    $clientId = $_GET['client_id'];
    $debts = $clientModel->getClientDebt($clientId);
    
    // Retornar la deuda en formato JSON
    echo json_encode($debts);
    exit();
}




?>