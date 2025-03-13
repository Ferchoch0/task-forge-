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

$clientModel = new clientModel($conn);
$userModel = new UserModel($conn);
$userId = $_SESSION['user_id'];

if (!$userModel->isEmailVerified($userId)) {
  header("Location: logout.php");
  exit();
}



$invoices = $clientModel->getUserInvoice($userId);
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

            <section class="stock-menu">
                <div class="stock-menu--options">
                    <button type="button" id="addNewBusiness" class="menu--button">
                        <span class="add icon"></span>
                        <span>Agregar Factura</span>
                    </button>
                </div>
                
            </section>



            <section>
                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>Cuit</th>
                            <th>Direccion Fiscal</th>
                            <th>Razon Social</th>
                            <th>Contacto</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
    if ($invoices) {
        foreach ($invoices as $invoice) {
            echo "<tr>";
            echo "<td>{$invoice['cuit']}</td>";
            echo "<td>{$invoice['address']}</td>" ;
            echo "<td>{$invoice['business_name']}</td>" ;
            echo "<td>{$invoice['contact']}</td>" ;
            echo "<td>{$invoice['invoice_type']}</td>";
            echo "</tr>"; 
        }
    } else {
        echo "<tr><td colspan='5'>No hay productos registrados</td></tr>";
    }
    ?>
</tbody>
                </table>
        </article>
    </div>
    
  </div>

  <div id="addModal" class="modal"> 
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Agregar Factura</h2>
        <form id="addInvoiceForm" action="invoiceController.php" method="POST">
                <input type="hidden" name="action" value="add">
                <select name="typeInvoice" id="typeInvoice" class="product">
                    <option value="A">Tipo A</option>
                    <option value="B">Tipo B</option>
                    <option value="C">Tipo C</option>
                </select>
                <input type="text" name="cuit" id="cuit" placeholder="Cuit">
                <input type="text" name="address" id="address" placeholder="Dirección Fiscal">
                <input type="text" name="businessName" id="businessName" placeholder="Razon Social">
                <input type="text" name="contact" id="contact" placeholder="Contacto">
            <button type="submit">Registrar Venta</button>
        </form>
    </div>
</div>

</body>

<script src="../View/src/assets/js/invoiceLoading.js"></script>

<?php

    require_once 'footer.php';

?>