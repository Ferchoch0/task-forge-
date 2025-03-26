<?php
session_start();
require_once 'head.php';
require_once '../Model/userModel.php';
require_once '../Model/balanceModel.php';
require_once '../Model/connection.php';



if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$userModel = new UserModel($conn);
$balanceModel = new BalanceModel($conn);
$userId = $_SESSION['user_id'];

if (!$userModel->isEmailVerified($userId)) {
  header("Location: logout.php");
  exit();
}

$username = $_SESSION['username'];



?>


<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/icon.css">
<link rel="stylesheet" href="src/assets/css/stock.css">
<link rel="stylesheet" href="src/assets/css/balance.css">



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>



</head>


<body>


  <?php require_once 'nav.php'; ?>
  <div class="dashboard-container">
    <div class="dashboard-container--ajust">
        <div class="stock-container">

            <h1>Balance</h1>
            

            <article class="balance-container">

              <section class="stats-container">
                <h2>Ventas Totales</h2>
                <div class="sales-container">
                  <span class="bank"></span>
                  <?php
                    $totalSales = $balanceModel->getSalesTotal($userId);
                    echo '<span id="totalSales">$' . $totalSales . '</span>';
                  ?>
                </div>
              </section>
              <section class="stats-container">
                <h2>Ventas mensuales</h2>
                <div class="sales-container">
                  <span class="bank"></span>
                  <?php
                    $MonthSales = $balanceModel->getSalesMonth($userId);
                    echo '<span id="monthSales">$' . $MonthSales . '</span>';
                  ?>
                </div>
              </section>

              <section class="stats-container">
                <h2>Balance de Hoy</h2>
                <canvas id="graficoBalance"></canvas>
              </section>
              <section class="stats-container">
                <h2>Flujo de Caja Hoy</h2>
                <canvas id="graficoBalancePorHora"></canvas>
              </section>

              <section class="stats-container">
                <h2>Balance de la Semana</h2>
                <canvas id="graficoBalanceSemanal"></canvas>
              </section>
              <section class="stats-container">
                <h2>Flujo de Caja Semanal</h2>
                <canvas id="graficoBalancePorSemana"></canvas>
              </section>

              <section class="stats-container">
                <h2>Productos Mas Vendidos</h2>
                <table>
                  <thead>
                    <tr>
                      <th>Producto</th>
                      <th>Cantidad</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $products = $balanceModel->getStockQuantity($userId);
                      foreach ($products as $product) {
                        echo '<tr>';
                        echo '<td> <span class="products-balance"></span>' . $product['products'] . '</td>';
                        echo '<td> <span class="quantity-container">' . $product['quantity'] . '</span> </td>';
                        echo '</tr>';
                      }
                    ?>
                  </tbody>
                </table>
              </section>
              <section class="stats-container">
                <h2>Clientes Frecuentes</h2>
                <table>
                  <thead>
                    <tr>
                      <th>Cliente</th>
                      <th>Cantidad</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $clients = $balanceModel->getClientQuantity($userId);
                      foreach ($clients as $client) {
                        echo '<tr>';
                        echo '<td> <span class="clients-balance"></span>' . $client['name'] . '</td>';
                        echo '<td> <span class="quantity-container">' . $client['quantity'] . '</span> </td>';
                        echo '</tr>';
                      }
                    ?>
                  </tbody>
                </table>
              </section>
              
            </article>

            
        </div>
    </div>
  </div>

  <script src="src/assets/js/balanceLoading.js"></script>

</body>


<?php

require_once 'footer.php';

?>