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





</head>


<body>

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
            echo "<td>$" . number_format($buy['amount'] * $buy['price_buy'],2) . "</td>";
            echo "<td>{$buy['fech']}</td>";
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
    <div class="modal-content little">
        <span class="close">&times;</span>
        <h2>Agregar Compra de Stock</h2>
        <form id="addBuyForm" action="BuyController.php" method="POST">
            <input type="hidden" name="action" value="add">
            <label for="product">Producto:</label> <p></p>
            <select name="product_id" class="product" id="product">
                <?php
                $products = $stockModel->getUserProducts($userId);
                foreach ($products as $product) {
                    echo "<option value='{$product['stock_id']}'>{$product['products']}</option>";
                }
                ?>
            </select> <p></p>

            <input type="number" name="amount" placeholder="Cantidad" id="amount" required min="1"><p></p>

            <input type="text"  id="priceBuy" name="priceBuy" placeholder="Precio" required><p></p>

            <label for="payment">Metodo de pago:</label> <p></p>
            <select name="payment" id="payment" class="product">
                <option value="Efectivo">Efectivo</option>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Transferencia">Transferencia</option>
            </select> <p></p>

            <button type="submit">Registrar Venta</button>
        </form>
    </div>
</div>

</body>

<script src="../View/src/assets/js/buyLoading.js"></script>


<?php

require_once 'footer.php';

?>