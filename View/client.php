<?php
session_start();
require_once 'head.php';
require_once '../Model/userModel.php';
require_once '../Model/clientModel.php';
require_once '../Model/connection.php';



if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
$clientModel = new ClientModel($conn);
$userModel = new UserModel($conn);
$userId = $_SESSION['user_id'];

if (!$userModel->isEmailVerified($userId)) {
  header("Location: logout.php");
  exit();
}

$username = $_SESSION['username'];

$clients = $clientModel->getUserClient($userId);

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
        <article class="stock-container">

            <h1>Clientes</h1>

            <section class="stock-menu">
                <div class="stock-menu--options">
                    <button class="menu--button">
                        <span class="add icon"></span>
                        <span>Agregar Cliente</span>
                    </button>
                </div>
                
            </section>



            <section>
                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Deuda Pagada</th>
                            <th>Deuda Faltante</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
    if ($clients) {
        foreach ($clients as $client) {
            echo "<tr>";
            echo "<td>{$client['name']}</td>";
            echo "<td>$" . number_format($client['debt_total'], 2) . "</td>";
            echo "<td>$" . number_format($client['debt_paid'], 2) . "</td>";
            echo "<td>
                    <div class='table--buttons'>
                        <button class='table--button delete-button' data-id='{$client['client_id']}'>
                            <span class='delete'></span>
                        </button>
                        <button class='table--button charge-button' data-id='{$client['client_id']}' data-debt-total='{$client['debt_total']}' data-debt-paid='{$client['debt_paid']}'>
                            <span class='edit'></span>
                        </button>
                    </div>
                </td>";
            echo "</tr>"; 
        }
    } else {
        echo "<tr><td colspan='3'>No hay productos registrados</td></tr>";
    }
    ?>
</tbody>
                </table>
        </article>
    </div>
    
  </div>

<div id="addModal" class="modal"> 
    <div class="modal-content little">
        <span class="close">&times;</span>
        <h2> Agregar Cliente</h2>
        <form id="addClientForm" method="POST">
            <input type="hidden" name="action" value="add">
            <input type="text" name="name" id="name" placeholder="Nombre" required>
            <input type="number" name="debt_total" id="debt_total" placeholder="Deuda Total" required min="1">

            <button type="submit">Registrar Venta</button>
        </form>
    </div>
</div>

<div id="chargeModal" class="modal"> 
    <div class="modal-content little">
        <span class="close close-charge">&times;</span>
        <h2> Cobrar deuda</h2>
        <form id="chargeClientForm" method="POST">
            <input type="hidden" name="action" value="charge">
            <input type="hidden" name="chargeClientId" id="chargeClientId">
            <input type="number" name="clientDebtTotal" id="clientDebtTotal" placeholder="Deuda Total" required min="1">
            <input type="number" name="clientDebtPaid" id="clientDebtPaid" placeholder="Deuda Pagada" required min="1">
            <button type="submit">Registrar Pago</button>
        </form>
</div>

</body>


<?php

    require_once 'footer.php';

?>

<script src="src/assets/js/clientLoading.js"></script>

</html>