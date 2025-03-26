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



            <section>
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
                        </tr>
                    </thead>
                    <tbody>
    <?php
    if ($buys) {
        foreach ($buys as $buy) {
            echo "<tr>";
            echo "<td>{$buy['products']}</td>";
            echo "<td>{$buy['amount']} {$buy['type_amount']}</td>" ;
            echo "<td>$" . number_format($buy['price_buy'], 2) . "</td>";
            echo "<td>{$buy['payment']}</td>";
            echo "<td>{$buy['name']}</td>";
            echo "<td>$" . number_format($buy['amount'] * $buy['price_buy'],2) . "</td>";
            echo "<td>{$buy['fech']}</td>";
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

<div id="addModal" class="modal"> 
    <div class="modal-content little">
        <span class="close">&times;</span>
        <p class="modal-title">Agregar Compra de Stock</p>
        <form id="addBuyForm" action="BuyController.php" method="POST">
            <input type="hidden" name="action" value="add">
            
            
            <div class="form-group">

                <label class="label-sub-title">
                    <span class="modal-sub-title">Producto Adquirido</span>
                    <div class="modal-field-container">
                        <select name="product_id" class="modal-field" class="product" id="product">
                            <?php
                                $products = $stockModel->getUserProducts($userId);
                                foreach ($products as $product) {
                                    echo "<option value='{$product['stock_id']}' data-price='{$product['price']}'>{$product['products']}</option>";
                                }
                            ?>
                        </select> 
                    </div>   
                </label>

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
                        <select name="supplier" class="modal-field" id="supplier" class="product">
                            <?php
                                $suppliers = $stockModel->getUserSupplier($userId);
                                foreach ($suppliers as $supplier) {
                                    echo "<option value='{$supplier['supplier_id']}'>{$supplier['name']}</option>";
                                }
                            ?>
                        </select>
                    </div> 
                </label>


                <button type="button" id="addNewSupplier" class="submit-button small-submit submenu--button"><span class="plus-button"></span></button>
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

<script src="../View/src/assets/js/buyLoading.js"></script>
<script src="../View/src/assets/js/supplierLoading.js"></script>

<?php

require_once 'footer.php';

?>