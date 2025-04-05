<?php 
session_start();
require_once '../Model/connection.php';
require_once '../Model/balanceModel.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$balanceModel = new BalanceModel($conn);
$userId = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $action = $_POST['action'];

  switch($action) {
    case "addCash":
      if (isset($_POST['description']) && isset($_POST['typeMov']) && isset($_POST['amount']) && isset($_POST['payment'])) {
        $description = $_POST['description'];
        $movType = $_POST['typeMov'];
        $amount = $_POST['amount'];
        $payment = $_POST['payment'];
    
        if ($balanceModel->addBalance($description, $movType, $amount, $payment, $userId)) {
          echo json_encode([
            "status" => "success",
            "message" => "Movimiento Cargado"
          ]);
        } else {
          echo json_encode([
            "status" => "error",
            "message" => "Error al intentar realizar movimiento"
          ]);
        }
      }
      break;
    case '':
    
    default:
      throw new Exception("Acción no válida");
  }
  
}

if ($_SERVER["REQUEST_METHOD"] === "GET"){

  $transactions = $balanceModel->getUserTransaction($userId);
  $action = $_GET['action'];

  switch($action){
    case 'getTable':
      if ($transactions) {
        foreach ($transactions as $transaction) {
            if ($transaction['payment'] === 'deuda') {
                continue;
            }

            if ($transaction['source'] === 'buy') {
                $type = 'Compra';
                $class = 'danger';
                $amount = $transaction['amount'] * $transaction['price'];
            } elseif ($transaction['source'] === 'sell') {
                $type = 'Venta';
                $class = 'success';
                $amount = $transaction['amount'] * $transaction['price'];
            } elseif ($transaction['source'] === 'cash') {
                $type = ($transaction['mov_type'] == 1) ? 'Ingreso' : 'Retiro';
                $class = 'info';
                $amount = $transaction['price'];
            } else {
                $type = 'Desconocido';
                $amount = 0;
            }

            echo "<tr>";
            echo "<td>{$transaction['products']}</td>";
            echo "<td><span class='type-container $class'>{$type}</span></td>";
            echo "<td>$" . number_format($amount, 2) . "</td>";
            echo "<td>{$transaction['payment']}</td>";
            echo "<td>{$transaction['date']}</td>";
            echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='5'>No hay transacciones registradas</td></tr>";
      }
      break;

    case 'getTableTotal':
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
  
        if ($transaction['payment'] === 'Efectivo') {
          $totalEfectivo += ($type === 'Ingreso') ? $amount : -$amount;
        } elseif ($transaction['payment'] === 'Tarjeta') {
          $totalTarjeta += ($type === 'Ingreso') ? $amount : -$amount;
        } elseif ($transaction['payment'] === 'Transferencia') {
          $totalTransferencia += ($type === 'Ingreso') ? $amount : -$amount;
        }
  
        $totalGeneral += ($type === 'Ingreso') ? $amount : -$amount;
      }
  
      echo "<tr>";
      echo "<td>" . number_format($totalEfectivo, 2) . "</td>";
      echo "<td>" . number_format($totalTarjeta, 2) . "</td>";
      echo "<td>" . number_format($totalTransferencia, 2) . "</td>";
      echo "<td>----</td>";
      echo "<td>" . number_format($totalGeneral, 2) . "</td>";
      echo "</tr>";

      break;
    default:
      throw new Exception("Acción no válida");
  }

 
}
?>