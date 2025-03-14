<?php
session_start();
require_once 'head.php';
require_once '../Model/userModel.php';
require_once '../Model/connection.php';



if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$userModel = new UserModel($conn);
$userId = $_SESSION['user_id'];

if (!$userModel->isEmailVerified($userId)) {
  header("Location: logout.php");
  exit();
}

$username = $_SESSION['username'];

require_once '../Model/stockModel.php';
$stockModel = new StockModel($conn);
$products = $stockModel->getUserProducts($userId); 

$lowStockCount = 0;
foreach ($products as $product) {
    if ($product['stock'] <= $product['stock_min'] && $product['stock'] != 0) {
        $lowStockCount++;
    }
}

$nullStockCount = 0;
foreach ($products as $product) {
    if ($product['stock'] == 0) {
        $nullStockCount++;
    }
}






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

            <h1>Stock</h1>

            <section class="stock-alert">
                <button id="stockWariningBtn">
                    <h2>Alertas: Stock bajo</h2>
                    <div class="stock-alert--item">
                        <span class="alert icon"></span>
                        <p><?= $lowStockCount ?> productos están por debajo del stock mínimo</p>
                    </div>
                </button>

                <button id="stockAlertBtn">
                    <h2>Alertas: Falta de stock</h2>
                    <div class="stock-alert--item">
                        <span class="alert icon"></span>
                        <p><?= $nullStockCount ?> productos están sin stock</p>
                    </div>
                </button>
                
            </section>

            <section class="stock-menu">
                <div class="stock-menu--options">
                    <button class="menu--button">
                        <span class="add icon"></span>
                        <span>Agregar producto</span>
                    </button>
                    <div class="stock-menu--search">
                        <input type="text" class="stock-menu--search-input" id="searchInput" placeholder="Buscar producto" onkeyup="filterTable()">
                        <button class="stock-menu--search-button">
                            <span class="search icon"></span>
                        </button>
                    </div>
                </div>
                
            </section>



            <section>
                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Stock</th>
                            <th>Stock Mínimo</th>
                            <th>Costo</th>
                            <th>Precio Recomendado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php

    if ($products) {
        foreach ($products as $product) {
            $lowStockClass = ($product['stock'] <= $product['stock_min'] && $product['stock'] != 0) ? 'low-stock' : '';
            $nullStockCount = ($product['stock'] == 0) ? 'null-stock' : '';
            $cost = $product['price'];
            $margin = 0.30;
            $iva = 0.21;

            $salePrice = $cost * (1 + $margin) * (1 + $iva);

            echo "<tr class='$lowStockClass $nullStockCount'>
                     <td>{$product['products']}</td>
                <td>{$product['stock']} {$product['type_amount']}</td>
                <td>{$product['stock_min']} {$product['type_amount']}</td>
                <td>$" . number_format($product['price'], 2) . "</td>
                <td>$" . number_format($salePrice, 2) . "</td>
                <td>
                    <div class='table--buttons'>
                    <button class='table--button delete-button' data-id='{$product['stock_id']}'>
                        <span class='delete'></span>
                    </button>
                    <button class='table--button edit-button' data-id='{$product['stock_id']}' data-name='{$product['products']}' data-stock='{$product['stock']}' data-min-stock='{$product['stock_min']}' data-type-amount='{$product['type_amount']}' data-price='{$product['price']}'>
                        <span class='edit'></span>
                    </button>
                    </div>
                </td>
              </tr>"; 
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


<!-- Agregar Productos -->
  
  <div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Agregar Producto</h2>
        <form id="addProductForm">
            <input type="hidden" name="action" value="add">
            <input type="text" id="productName" name="productName" placeholder="Nombre del producto" required>
            <div class="form-group-stock">
                <input type="number" id="productStock" name="productStock" placeholder="Stock" required>
                <input type="number" id="productMinStock" name="productMinStock" placeholder="Stock Mínimo" required>
            </div>
            <select id="productTypeAmount" name="productTypeAmount" required>
                <option value="" disabled selected>Tipo de unidad</option>
                <option value="kg.">Kilogramos</option>
                <option value="u.">Unidades</option>
            </select>
            <input type="number" step="0.01" id="productPrice" name="productPrice" placeholder="Costo (sin signo)" required>


            <button type="submit">Guardar</button>
        </form>
    </div>
</div>

<!-- Editar Productos -->

<div id="editProductModal" class="modal">
    <div class="modal-content">
        <span class="close-edit close">&times;</span>
        <h2>Editar Producto</h2>
        <form id="editProductForm">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="editProductId" name="editProductId">
            <input type="text" id="editProductName" name="editProductName" placeholder="Nombre del producto" required>
            <div class="form-group-stock">
                <input type="number" id="editProductStock" name="editProductStock" placeholder="Stock" required>
                <input type="number" id="editProductMinStock" name="editProductMinStock" placeholder="Stock Mínimo" required>
            </div>
            <select id="editProductTypeAmount" name="editProductTypeAmount" required>
                <option value="" disabled selected>Tipo de unidad</option>
                <option value="kg.">Kilogramos</option>
                <option value="u.">Unidades</option>
            </select>
            <input type="number" step="0.01" id="editProductPrice" name="editProductPrice" placeholder="Costo (sin signo)" required>
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</div>

</body>

<script src="../View/src/assets/js/stockLoading.js"></script>

<?php

    require_once 'footer.php';

?>