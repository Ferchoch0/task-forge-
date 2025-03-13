<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/clientModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

$clientModel = new ClientModel($conn);
$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Agregar cliente
    if (isset($_POST['name']) && isset($_POST['debt_total'])) {
        $name = $_POST['name'];
        $debt_total = $_POST['debt_total'];

        if ($clientModel->addClient($name, $debt_total, $userId)) {
            header("Location: ../View/client.php?success=1");
        } else {
            header("Location: ../View/client.php?error=client");
        }
        exit();
    }

    // Cobrar cliente
    if (isset($_POST['chargeClientId']) && isset($_POST['clientdebtTotal']) && isset($_POST['clientdebtPaid'])) {
        $chargeClientId = $_POST['chargeClientId'];
        $debtTotal = $_POST['clientdebtTotal'];
        $debtPaid = $_POST['clientdebtPaid'];

        if ($clientModel->chargeClient($debtTotal, $debtPaid, $chargeClientId)) {
            header("Location: ../View/client.php?success=2");
        } else {
            header("Location: ../View/client.php?error=client");
        }
        exit();
    }

    // Eliminar cliente
    if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action']) && $data['action'] === 'delete' && isset($data['client_id'])) {
            $deleteClientId = $data['client_id'];

            if ($clientModel->deleteClient($deleteClientId)) {
                echo "Cliente eliminado correctamente.";
            } else {
                echo "Error al eliminar el cliente.";
            }
        } else {
            echo "Acción no válida o falta ID para eliminar.";
        }
        exit();
    }
}
?>