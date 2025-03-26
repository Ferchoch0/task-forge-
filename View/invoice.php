<?php
session_start();
require_once 'head.php';
require_once '../Model/userModel.php';
require_once '../Model/clientModel.php';
require_once '../Model/invoiceModel.php';
require_once '../Model/connection.php';



if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$clientModel = new clientModel($conn);
$invoiceModel = new InvoiceModel($conn);
$userModel = new UserModel($conn);
$userId = $_SESSION['user_id'];

if (!$userModel->isEmailVerified($userId)) {
  header("Location: logout.php");
  exit();
}



$invoices = $invoiceModel->getUserInvoice($userId);
$username = $_SESSION['username'];



?>


<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/icon.css">
<link rel="stylesheet" href="src/assets/css/stock.css">





</head>


<body>

  <?php require_once 'nav.php'; ?>
  <div class="dashboard-container">
    <div class="dashboard-container--ajust">
        <article class="stock-container">

            <h1>Facturas</h1>

            <section>
                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>Cuit</th>
                            <th>Direccion Fiscal</th>
                            <th>Razon Social</th>
                            <th>Contacto</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
    if ($invoices) {
        foreach ($invoices as $invoice) {
            echo "<tr>";
            echo "<td>{$invoice['cuit']}</td>";
            echo "<td>{$invoice['address']}</td>" ;
            echo "<td>{$invoice['name']}</td>" ;
            echo "<td>{$invoice['contact']}</td>" ;
            echo "<td>{$invoice['invoice_type']}</td>";
            echo "<td>{$invoice['fech']}</td>";
            echo "</tr>"; 
        }
    } else {
        echo "<tr><td colspan='6'>No hay productos registrados</td></tr>";
    }
    ?>
</tbody>
                </table>
        </article>
    </div>
    
  </div>


</body>

<script src="../View/src/assets/js/invoiceLoading.js"></script>

<?php

    require_once 'footer.php';

?>