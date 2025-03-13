<?php
session_start();
require_once 'head.php';
require_once '../Model/userModel.php';
require_once '../Model/stockModel.php';
require_once '../Model/clientModel.php';
require_once '../Model/connection.php';



if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$stockModel = new StockModel($conn);
$userModel = new UserModel($conn);
$clientModel = new ClientModel($conn);
$userId = $_SESSION['user_id'];

if (!$userModel->isEmailVerified($userId)) {
  header("Location: logout.php");
  exit();
}



$sells = $stockModel->getUserSells($userId);
$username = $_SESSION['username'];



?>


<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/icon.css">
<link rel="stylesheet" href="src/assets/css/stock.css">
<link rel="stylesheet" href="src/assets/css/buyAndSell.css">
<link rel="stylesheet" href="src/assets/css/invoice.css">






</head>


<body>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

 <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


  <?php require_once 'nav.php'; ?>
  <div class="dashboard-container">
    <div class="dashboard-container--ajust">
        <article class="stock-container">

            <h1>Ventas</h1>

            <section class="stock-menu">
                <div class="stock-menu--options">
                    <button class="menu--button">
                        <span class="add icon"></span>
                        <span>Agregar venta</span>
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
                            <th>Precio total</th>
                            <th>Factura</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
    if ($sells) {
        foreach ($sells as $sell) {
            $invoice = $sell['invoice_type'] ? $sell['invoice_type'] : "No"; 
            echo "<tr>";
            echo "<td>{$sell['products']}</td>";
            echo "<td>{$sell['amount']} {$sell['type_amount']}</td>";
            echo "<td>$" . number_format($sell['price_sell'], 2) . "</td>";
            echo "<td>{$sell['payment']}</td>";
            echo "<td>$" .  number_format($sell['amount'] * $sell['price_sell'], 2) . "</td>";
            echo "<td>{$invoice}</td>";
            echo "<td>{$sell['fech']}</td>";
            echo "</tr>"; 
        }
    } else {
        echo "<tr><td colspan='6'>No hay productos registrados</td></tr>";
    }
    ?>
</tbody>
                </table>
            </section>
        </article>
    </div>
    
  </div>

<div id="addModal" class="modal"> 
    <div class="modal-content little">
        <span class="close">&times;</span>
        <h2>Agregar Venta</h2>
        <form id="addSellForm" action="sellController.php" method="POST">
            <input type="hidden" name="action" value="add">
            <label for="product">Producto:</label> <p></p>
            <div class="form-group">
                <select name="product_id" class="product" id="product">
                    <?php
                        $products = $stockModel->getUserProducts($userId); // Obtener productos del usuario
                        foreach ($products as $product) {
                            echo "<option value='{$product['stock_id']}' data-price='{$product['price']}'> {$product['products']} </option>";
                        }
                    ?>
                </select> <p></p>

                <input type="number" name="amount" placeholder="Cantidad" id="amount" required min="1"><p></p>

                <input type="text"  id="priceSell" name="priceSell" placeholder="Precio" readonly><p></p>

                <button type="button" id="addProductBtn">cargar archivo</button>
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
                        <td  class="change-table--footer" id="totalSell"></td>
                    </tr>
                </tbody>
            </table>

            <div class="invoice-group--checkbox">
                <input type="checkbox" name="invoice" id="invoice" value="off" class="invoice-checkbox">
                <label for="invoice">Factura</label>
            </div>

            <div id="invoiceGroup"  class="invoice-group">

                <select name="business_name" class="product" id="business_name">
                    <?php
                        $invoices = $clientModel->getUserInvoice($userId); // Obtener facturas del usuario
                        foreach($invoices as $invoice) {
                            echo "<option value='{$invoice['invoice_id']}'> {$invoice['business_name']} </option>";
                        }
                    ?>
                </select>

                <button type="button" id="addNewBusiness" class="submenu--button">+</button>
            </div>

            <label for="payment">Metodo de pago:</label> <p></p>
            <select name="payment" id="payment" class="product">
                <option value="Efectivo">Efectivo</option>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Transferencia">Transferencia</option>
            </select>
            <button type="submit">Registrar Venta</button>
        </form>
    </div>
</div>

<div id="addSubModal-1" class="modal"> 
    <div class="modal-content">
        <span class="close--sub">&times;</span>
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
            <button type="submit">Registrar Factura</button>
        </form>
    </div>
</div>

</body>

<script src="../View/src/assets/js/sellLoading.js"></script>
<script src="../View/src/assets/js/invoiceLoading.js"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const productSelect = document.getElementById("product");
    const priceInput = document.getElementById("priceSell");

    // Función para actualizar el precio
    function updatePrice() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = selectedOption.getAttribute("data-price");
        priceInput.value = price ? price : "";
    }

    // Llamar a la función al cargar y cuando cambie el producto
    updatePrice();
    productSelect.addEventListener("change", updatePrice);
});
</script>


<?php

require_once 'footer.php';

?>