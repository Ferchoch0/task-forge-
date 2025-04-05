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



$buys = $stockModel->getUserBuys($userId);
$username = $_SESSION['username'];



?>


<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/icon.css">
<link rel="stylesheet" href="src/assets/css/stock.css">
<link rel="stylesheet" href="src/assets/css/buyAndSell.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>



</head>


<body>


<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

  <?php require_once 'nav.php'; ?>
  <div class="dashboard-container">
    <div class="dashboard-container--ajust">
        <article class="stock-container">

            <h1>Compras</h1>

            <section class="stock-menu">
                <div class="stock-menu--options">
                    <button class="menu--button">
                        <span class="add icon"></span>
                        <span>Agregar Compra</span>
                    </button>
                </div>
                
            </section>



            <section class="stock-table-container">
                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio/u</th>
                            <th>Metodo</th>
                            <th>Proveedor</th>
                            <th>Precio total</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
  
                    </tbody>
                </section>
            </table>
        </article>
    </div>
    
  </div>

<div id="addModal" class="modal"> 
    <div class="modal-content little">
        <span class="close">&times;</span>
        <p class="modal-title">Agregar Compra de Stock</p>
        <form id="addBuyForm" action="BuyController.php" method="POST">
            <input type="hidden" name="action" value="addBuy">
            
            
            <div class="form-group">

                <label class="label-sub-title">
                    <span class="modal-sub-title">Producto Adquirido</span>
                    <div class="modal-field-container">
                        <select name="product_id" class="modal-field select-product" class="product" id="product">

                        </select> 
                    </div>   
                </label>

                <button type="button" id="addNewProduct" class="submit-button small-submit submenu--button"  style="margin-right: 1rem;"><span class="plus-button"></span></button>


                <label class="label-sub-title">
                    <span class="modal-sub-title">Cantidad</span>
                    <div class="modal-field-container">
                        <input type="number" class="modal-field" name="amount" placeholder="Ingresar Cantidad" id="amount" autocomplete="off" required min="1">
                    </div>   
                </label>

                <label class="label-sub-title">
                    <span class="modal-sub-title">Precio</span>
                    <div class="modal-field-container">
                        <span class="field-signed">$</span>
                        <input type="text" class="modal-field"  id="priceBuy" name="priceBuy" placeholder="Ingresar Precio" autocomplete="off" required>
                    </div>   
                </label>


                    <button type="button" id="addProductBtn" class="submit-button">cargar producto</button>
            </div>

            <table  class="change-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio total</th>
                    </tr>
                </thead>
                <tbody  id="changeTableBody">
                            <!-- Los productos cargados aparecerán aquí -->
                </tbody>
                <tbody>
                    <tr>
                        <td class="change-table--footer">Total</td>
                        <td  class="change-table--footer" id="totalAmount"></td>
                        <td  class="change-table--footer" id="totalBuy"></td>
                    </tr>
                </tbody>
            </table>
            <div class="container-group">
            <div class="invoice-group check-group">
                <label class="label-sub-title">
                    <span class="modal-sub-title">Proveedor</span>
                    <div class="modal-field-container small-field">
                        <select name="supplier" class="modal-field select-supplier" id="supplier" class="product">
                            
                        </select>
                    </div> 
                </label>


                <button type="button" id="addNewSupplier" class="submit-button small-submit submenu--button2"><span class="plus-button"></span></button>
            </div>
            <label class="label-sub-title">
                <span class="modal-sub-title">Metodo de Pago</span>
                <div class="modal-field-container small-field">
                    <select name="payment" class="modal-field" id="payment" class="product">
                        <option value="" disabled selected>Seleccionar</option>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Tarjeta">Tarjeta</option>
                        <option value="Transferencia">Transferencia</option>
                    </select>
                </div> 
            </label> 
            </div>


            <span class="model-separator"></span>
                
            <div class="modal-submit">
                <button type="submit" class="submit-button">Registrar Ventas</button>
            </div>
        </form>
    </div>

</div>

<div id="addSubModal-1" class="modal">
    <div class="modal-content">
        <span class="close--sub">&times;</span>
        <p class="modal-title">Agregar Producto</p>
        <form id="addProductForm">
            <input type="hidden" name="action" value="addProduct">
            <input type="hidden" id="productStock" name="productStock" value="0">
            <label class="label-sub-title">
                <span class="modal-sub-title">Nombre de Producto</span>
                <div class="modal-field-container">
                    <input type="text" class="modal-field" id="productName" name="productName" placeholder="Ingresar Nombre" autocomplete="off" required>
                </div>   
            </label>

                <label class="label-sub-title">
                    <span class="modal-sub-title">Stock Minimo</span>
                    <div class="modal-field-container">
                        <input type="number" class="modal-field" id="productMinStock" name="productMinStock" placeholder="Ingresa Cantidad" autocomplete="off" required>
                    </div>
                </label>

            <label class="label-sub-title">
                    <span class="modal-sub-title">Tipo de unidad</span>
                    <div class="modal-field-container">
                        <select id="productTypeAmount" class="modal-field" name="productTypeAmount" required>
                            <option value="" disabled selected>Seleccionar</option>
                            <option value="u.">Unidades</option>
                            <option value="kg.">Kilogramos</option>
                        </select>
                    </div>
                </label>

            <label class="label-sub-title">
                    <span class="modal-sub-title">Costo del Producto</span>
                    <div class="modal-field-container">
                        <span class="field-signed">$</span>
                        <input type="number" class="modal-field" step="0.01" id="productPrice" name="productPrice" placeholder="Costo (sin signo)" autocomplete="off" required>
                    </div>
            </label>

            <span class="model-separator"></span>

            <div class="modal-submit">
                <button type="submit" class="submit-button">Guardar Producto</button>
            </div>
        </form>
    </div>
</div>


<div id="addSubModal-2" class="modal"> 
        <div class="modal-content">
            <span class="close--sub2">&times;</span>
            <p class="modal-title">Agregar Proveedor</p>
            <form id="addSupplierForm" method="POST">
                <input type="hidden" name="action" value="addSupplier">
                <label class="label-sub-title">
                    <span class="modal-sub-title">Nombre</span>
                    <div class="modal-field-container">
                        <input type="text" class="modal-field" name="supplier" id="supplier" placeholder="Ingresar Nombre" autocomplete="off" required>
                    </div>   
                </label>
                <label class="label-sub-title">
                    <span class="modal-sub-title">Contacto</span>
                    <div class="modal-field-container">
                        <input type="text" class="modal-field" name="contact" id="contact" placeholder="Ingresar Contacto" autocomplete="off" required>
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

<script src="../View/src/assets/js/buyLoading.js"></script>
<script src="../View/src/assets/js/stockLoading.js"></script>
<script src="../View/src/assets/js/supplierLoading.js"></script>

<?php

require_once 'footer.php';

?>