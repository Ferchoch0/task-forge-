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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>




</head>


<body>


<?php require_once 'nav.php'; ?>
<div class="dashboard-container">
    <div class="dashboard-container--ajust">
        <article class="stock-container">

            <h1>Clientes</h1>

            <section class="stock-menu">
                <div class="stock-menu--options">
                    <button class="submenu--button">
                        <span class="add icon"></span>
                        <span>Agregar Cliente</span>
                    </button>
                </div>
                <div class="stock-menu--search">
                    <input type="text" class="stock-menu--search-input" id="searchInput" placeholder="Buscar producto" onkeyup="filterTable()">
                    <button class="stock-menu--search-button">
                        <span class="search icon"></span>
                    </button>
                </div>
                
            </section>



            <section class="stock-table-container">
                <table class="stock-table client-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Deuda</th>
                            <th>contacto</th>
                            <th>Acciones</th>
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
        <p class="modal-title">Agregar Cliente</p>
        <form id="addClientForm" method="POST">
                    <input type="hidden" name="action" value="addClient">
                    <label class="label-sub-title">
                        <span class="modal-sub-title">Razon Social</span>
                        <div class="modal-field-container">
                            <input type="text" class="modal-field" name="name" id="name" placeholder="Ingresar Razon Social" autocomplete="off">
                        </div>   
                    </label>
                    
                    <label class="label-sub-title">
                        <span class="modal-sub-title">Tipo de Factura</span>
                        <div class="modal-field-container">
                            <select name="typeInvoice" class="modal-field" id="typeInvoice" class="product">
                                <option value="" disabled selected>Seleccionar</option>
                                <option value="A">Tipo A</option>
                                <option value="B">Tipo B</option>
                                <option value="C">Tipo C</option>
                            </select>
                        </div>   
                    </label>

                    <label class="label-sub-title">
                        <span class="modal-sub-title">Cuit</span>
                        <div class="modal-field-container">
                            <input type="text" class="modal-field" name="cuit" id="cuit" placeholder="Ingresar Cuit" autocomplete="off">
                        </div>   
                    </label>

                    <label class="label-sub-title">
                        <span class="modal-sub-title">Dir. Fiscal</span>
                        <div class="modal-field-container">
                            <input type="text" class="modal-field" name="address" id="address" placeholder="Ingresar Dirección Fiscal" autocomplete="off">
                        </div>   
                    </label>

                    <label class="label-sub-title">
                        <span class="modal-sub-title">Contacto</span>
                        <div class="modal-field-container">
                            <input type="text" class="modal-field" name="contact" id="contact" placeholder="Ingresar Contacto" autocomplete="off">
                        </div>   
                    </label>

                    <span class="model-separator"></span>
                    
                    <div class="modal-submit">
                        <button type="submit" class="submit-button">Registrar Factura</button>
                    </div>
            </form>
    </div>
</div>

<div id="chargeModal" class="modal"> 
    <div class="modal-content little">
        <span class="close close-charge">&times;</span>
        <p class="modal-title"> Cobrar deuda</p>
        <form id="chargeClientForm" method="POST">
            <input type="hidden" name="action" value="chargeClient">
            <input type="hidden" name="chargeClientId" id="chargeClientId">
            <label class="label-sub-title">
                <span class="modal-sub-title">Deuda Actual</span>
                <div class="modal-field-container">
                    <span class="field-signed">$</span>
                    <input type="number" class="modal-field" name="clientDebtTotal" id="clientDebtTotal" placeholder="Deuda Total" autocomplete="off" required min="1" readonly>
                </div>   
            </label>

            <label class="label-sub-title">
                <span class="modal-sub-title">Monto a Pagar</span>
                <div class="modal-field-container">
                    <span class="field-signed">$</span>
                    <input type="number" class="modal-field" name="clientDebtPaid" id="clientDebtPaid" placeholder="Valor A Pagar" required min="1">
                </div>   
            </label>

            <span class="model-separator"></span>
                    
            <div class="modal-submit">
                <button type="submit" class="submit-button">Registrar Pago</button>
            </div>
        </form>
    </div>
</div>

<div id="historyModal" class="modal"> 
    <div class="modal-content little">
        <span class="close close-history">&times;</span>
        <p class="modal-title"> Cobrar deuda</h2>
        <form id="historyClientForm" method="POST">
            <input type="hidden" name="action" value="history">
            <input type="hidden" name="historyClientId" id="historyClientId">
        <table class="change-table">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody id="historyTable"> 
                            <!-- El historial de deuda aparecera aquí -->
                    </tbody>
                </table>
        </form>
    </div>
</div>

</body>


<?php

    require_once 'footer.php';

?>

<script src="src/assets/js/clientLoading.js"></script>

</html>