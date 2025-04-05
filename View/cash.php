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
$balanceModel = new BalanceModel($conn);
$userModel = new UserModel($conn);
$userId = $_SESSION['user_id'];

if (!$userModel->isEmailVerified($userId)) {
  header("Location: logout.php");
  exit();
}

$transactions = $balanceModel->getUserTransaction($userId);
$username = $_SESSION['username'];


?>

<link rel="stylesheet" href="src/assets/css/icon.css">
<link rel="stylesheet" href="src/assets/css/stock.css">
<link rel="stylesheet" href="src/assets/css/cash.css">
<link rel="stylesheet" href="src/assets/css/global.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>



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

                <button  id="downloadPDF" class="submit-button success">
                        <span class="remove icon"></span>
                        <span>Cerrar Caja</span>
                </button>
                <section class="stock-table-container">
                <table class="stock-table cash-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>T. Movimiento</th>
                            <th>Cantidad</th>
                            <th>Metodo</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody class="cash-data">
                    
                    </tbody>


                    <thead class="cash-menu--total">
                      <tr>
                        <th>Efectivo</th>
                        <th>Tarjeta</th>
                        <th>Transferencia</th>
                        <th>----</th>
                        <th>TOTAL</th>
                      </tr>
                    </thead>

                    <tbody class="cash-total">
                      
                    </tbody>
                  </table>
                  </section>

                  </div>

                  <div class="cash-menu--balance"> 
                    <button class="cash--menu" data-type="1">
                        <span class="add icon"></span>
                        <span>Agregar Dinero</span>
                    </button>
                    <button class="cash--menu" data-type="0">
                        <span class="remove icon"></span>
                        <span>Retirar Dinero</span>
                    </button>
                </div> 

            </section>

        </article>
    </div>
   </div>

   <div id="cashModal" class="modal"> 
    <div class="modal-content little">
        <span class="close close-cash">&times;</span>
        <p class="modal-title">Agregar Movimiento</p>
        <form id="addCashForm" method="POST">
            <input type="hidden" name="action" value="addCash">
            <input type="hidden" name="typeMov" id="typeMov"> 
            <label class="label-sub-title">
              <span class="modal-sub-title">Descripcion</span>
              <div class="modal-field-container">
                <input type="text" class="modal-field" name="description" id="description" placeholder="Ingresar una Descripcion" autocomplete="off" required>
              </div>   
            </label>

            <label class="label-sub-title">
              <span class="modal-sub-title">Cantidad</span>
              <div class="modal-field-container">
                <input type="number" class="modal-field" name="amount" id="amount" placeholder="Ingresar Cantidad" autocomplete="off" required min="1">
              </div>   
            </label>

            <label class="label-sub-title">
              <span class="modal-sub-title">Tipo de Movimiento</span>
              <div class="modal-field-container">
                <select name="payment" class="modal-field" id="payment" class="product">
                  <option value="" disabled selected>Seleccionar</option>
                  <option value="Efectivo">Efectivo</option>
                  <option value="Tarjeta">Tarjeta</option>
                  <option value="Transferencia">Transferencia</option>
                </select>
              </div>   
            </label>            

            <span class="model-separator"></span>

            <div class="modal-submit">
              <button type="submit" class="submit-button">Registrar Movimiento</button>
            </div>
        </form>
    </div>
</div>

</body>

<script src="../View/src/assets/js/cashLoading.js"></script>



<?php

    require_once 'footer.php';

?>