<?php
session_start();
require_once 'head.php';
require_once '../Model/userModel.php';
require_once '../Model/stockModel.php';
require_once '../Model/connection.php';



if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
$stockModel = new StockModel($conn);
$userModel = new UserModel($conn);
$userId = $_SESSION['user_id'];

if (!$userModel->isEmailVerified($userId)) {
  header("Location: logout.php");
  exit();
}

$transactions = $stockModel->getUserTransaction($userId);
$username = $_SESSION['username'];


?>


<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/icon.css">
<link rel="stylesheet" href="src/assets/css/stock.css">
<link rel="stylesheet" href="src/assets/css/cash.css">




</head>


<body>


<?php require_once 'nav.php'; ?>
  <div class="dashboard-container">
    <div class="dashboard-container--ajust">
          <div class="cash-container--title">
            <h1>Caja</h1>
          </div> 
        <article class="cash-container">   
            <section class="cash-menu">
                <h2>Transacciones</h2>

                <div class="cash-menu--options">

                <button class="success stock-menu--button">
                        <span class="remove icon"></span>
                        <span>Cerrar Caja</span>
                </button>

                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>T. Movimiento</th>
                            <th>Cantidad</th>
                            <th>Metodo</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody>
                      <?php
                            if ($transactions) {
                                foreach ($transactions as $transaction) {
                                    $type = $transaction['source'] === 'buy' ? 'Retiro' : 'Ingreso';
                                    echo "<tr>";
                                    echo "<td>{$transaction['products']}</td>";
                                    echo "<td>{$type}</td>";
                                    echo "<td> $" . number_format(($transaction['amount'] * $transaction['price']), 2) . "</td>";
                                    echo "<td>{$transaction['payment']}</td>";
                                    echo "<td>{$transaction['date']}</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay transacciones registradas</td></tr>";
                            }
                      ?>
                    </tbody>
                  

                    <?php
                      $totalEfectivo = 0;
                      $totalTarjeta = 0;
                      $totalTransferencia = 0;
                      $totalGeneral = 0;

                      if ($transactions) {
                        foreach ($transactions as $transaction) {
                          $subtotal = $transaction['amount'] * $transaction['price'];
                          if ($transaction['source'] === 'buy') {
                            if ($transaction['payment'] === 'Efectivo') {
                              $totalEfectivo -= $subtotal;
                            } elseif ($transaction['payment'] === 'Tarjeta') {
                              $totalTarjeta -= $subtotal;
                            } elseif ($transaction['payment'] === 'Transferencia') {
                              $totalTransferencia -= $subtotal;
                          }
                          $totalGeneral -= $subtotal;
                        } elseif($transaction['source'] === 'sell') {
                          if ($transaction['payment'] === 'Efectivo') {
                            $totalEfectivo += $subtotal;
                          } elseif ($transaction['payment'] === 'Tarjeta') {
                            $totalTarjeta += $subtotal;
                          } elseif ($transaction['payment'] === 'Transferencia') {
                            $totalTransferencia += $subtotal;
                        }
                        $totalGeneral += $subtotal;
                        }
                            
                           
                        }
                      }
                    ?>

                    <thead class="cash-menu--total">
                      <tr>
                        <th>Efectivo</th>
                        <th>Tarjeta</th>
                        <th>Transferencia</th>
                        <th>----</th>
                        <th>TOTAL</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr>
                        <td>$<?php echo number_format($totalEfectivo, 2); ?></td>
                        <td>$<?php echo number_format($totalTarjeta, 2); ?></td>
                        <td>$<?php echo number_format($totalTransferencia, 2) ?></td>
            <td>----</td>
            <td>$<?php echo number_format($totalGeneral, 2); ?></td>
        </tr>
</tbody>
                  </table>
                  </div>

                  <div class="cash-menu--balance"> 
                    <button class="stock-menu--button">
                        <span class="add icon"></span>
                        <span>Agregar Dinero</span>
                    </button>
                    <button class="stock-menu--button">
                        <span class="remove icon"></span>
                        <span>Retirar Dinero</span>
                    </button>
                </div> 

            </section>

        </article>
    </div>
   </div>


<?php

    require_once 'footer.php';

?>