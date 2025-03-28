<?php 
session_start();
require_once '../Model/connection.php';
require_once '../Model/balanceModel.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

ini_set('display_errors', 1);  // Muestra los errores de PHP
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 

$balanceModel = new BalanceModel($conn);
$userId = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Agregar saldo
  if (isset($_POST['description']) && isset($_POST['typeMov']) && isset($_POST['amount']) && isset($_POST['payment'])) {
    $description = $_POST['description'];
    $movType = $_POST['typeMov'];
    $amount = $_POST['amount'];
    $payment = $_POST['payment'];

    if ($balanceModel->addBalance($description, $movType, $amount, $payment, $userId)) {
      header("Location: ../View/cash.php?success=1");
    } else {
      header("Location: ../View/cash.php?error=balance");
    }
    exit();
  }
}

if ($_SERVER["REQUEST_METHOD"] === "GET"){

  if (isset($_GET['action'])) {

    $transactions = $balanceModel->getUserTransaction($userId);

    if ($_GET['action'] === 'getTable') {
      // Solo se devuelven las transacciones sin cálculos de totales
      if ($transactions) {
          foreach ($transactions as $transaction) {
              if ($transaction['payment'] === 'deuda') {
                  continue;
              }

              if ($transaction['source'] === 'buy') {
                  $type = 'Compra';
                  $amount = $transaction['amount'] * $transaction['price'];
              } elseif ($transaction['source'] === 'sell') {
                  $type = 'Venta';
                  $amount = $transaction['amount'] * $transaction['price'];
              } elseif ($transaction['source'] === 'cash') {
                  $type = ($transaction['mov_type'] == 1) ? 'Ingreso' : 'Retiro';
                  $amount = $transaction['price'];
              } else {
                  $type = 'Desconocido';
                  $amount = 0;
              }

              // Mostrar las filas de transacciones
              echo "<tr>";
              echo "<td>{$transaction['products']}</td>";
              echo "<td>{$type}</td>";
              echo "<td>$" . number_format($amount, 2) . "</td>";
              echo "<td>{$transaction['payment']}</td>";
              echo "<td>{$transaction['date']}</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No hay transacciones registradas</td></tr>";
      }
      exit();
  } 

  elseif ($_GET['action'] === 'getTableTotal') {
    $totalEfectivo = 0;
    $totalTarjeta = 0;
    $totalTransferencia = 0;
    $totalGeneral = 0;

    foreach ($transactions as $transaction) {
      if ($transaction['payment'] === 'deuda') {
          continue;
      }

      if ($transaction['source'] === 'buy') {
        $type = 'Retiro';
        $amount = $transaction['amount'] * $transaction['price'];
      } elseif ($transaction['source'] === 'sell') {
        $type = 'Ingreso';
        $amount = $transaction['amount'] * $transaction['price'];
      } elseif ($transaction['source'] === 'cash') {
        $type = ($transaction['mov_type'] == 1) ? 'Ingreso' : 'Retiro';
        $amount = $transaction['price'];
      } else {
        $type = 'Desconocido';
        $amount = 0;
      }

      // Acumular totales según el tipo de pago
      if ($transaction['payment'] === 'Efectivo') {
        $totalEfectivo += ($type === 'Ingreso') ? $amount : -$amount;
      } elseif ($transaction['payment'] === 'Tarjeta') {
        $totalTarjeta += ($type === 'Ingreso') ? $amount : -$amount;
      } elseif ($transaction['payment'] === 'Transferencia') {
        $totalTransferencia += ($type === 'Ingreso') ? $amount : -$amount;
      }

      $totalGeneral += ($type === 'Ingreso') ? $amount : -$amount;
  }

      // Mostrar los totales
      echo "<tr>";
      echo "<td>" . number_format($totalEfectivo, 2) . "</td>";
      echo "<td>" . number_format($totalTarjeta, 2) . "</td>";
      echo "<td>" . number_format($totalTransferencia, 2) . "</td>";
      echo "<td>----</td>";
      echo "<td>" . number_format($totalGeneral, 2) . "</td>";
      echo "</tr>";
      exit();
  }
}

}
?>