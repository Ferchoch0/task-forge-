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




$username = $_SESSION['username'];



?>


<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/icon.css">
<link rel="stylesheet" href="src/assets/css/stock.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>



</head>


<body>

  <?php require_once 'nav.php'; ?>
  <div class="dashboard-container">
    <div class="dashboard-container--ajust">
        <article class="stock-container">

            <h1>Proveedores</h1>

            <section class="stock-menu">
                <div class="stock-menu--options">
                    <button class="submenu--button">
                        <span class="add icon"></span>
                        <span>Agregar Proveedor</span>
                    </button>
                </div>
                
            </section>



            <section class="stock-table-container">
                <table class="stock-table supplier-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Contacto</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </section>
        </article>
    </div>
    
  </div>

<div id="addSubModal-1" class="modal"> 
    <div class="modal-content little">
        <span class="close--sub">&times;</span>
        <p class="modal-title">Agregar Proveedor</p>
            <form id="addSupplierForm" method="POST">
                <input type="hidden" name="action" value="add">
                <label class="label-sub-title">
                    <span class="modal-sub-title">Nombre</span>
                    <div class="modal-field-container">
                        <input type="text" class="modal-field" name="supplier" id="supplier" placeholder="Ingresar Nombre" required>
                    </div>   
                </label>
                <label class="label-sub-title">
                    <span class="modal-sub-title">Contacto</span>
                    <div class="modal-field-container">
                        <input type="text" class="modal-field" name="contact" id="contact" placeholder="Ingresar Contacto" required>
                    </div>   
                </label>

                <span class="model-separator"></span>


                <div class="modal-submit">
                    <button type="submit" class="submit-button">Registrar Proveedor</button>
                </div>
            </form>
    </div>
</div>

</body>

<script src="../View/src/assets/js/supplierLoading.js"></script>


<?php

require_once 'footer.php';

?>