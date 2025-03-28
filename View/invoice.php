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

            <section class="stock-table-container">
                <table class="stock-table invoice-table">
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

                    </tbody>
                </table>
            </section>
        </article>
    </div>
    
  </div>


</body>

<script src="../View/src/assets/js/invoiceLoading.js"></script>

<?php

    require_once 'footer.php';

?>