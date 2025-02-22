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
                    <button class="stock-menu--button">
                        <span class="add icon"></span>
                        <span>Agregar producto</span>
                    </button>
                    <div class="stock-menu--search">
                        <input type="text" class="stock-menu--search-input" placeholder="Buscar producto">
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
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php

    if ($products) {
        foreach ($products as $product) {
            $lowStockClass = ($product['stock'] <= $product['stock_min'] && $product['stock'] != 0) ? 'low-stock' : '';
            $nullStockCount = ($product['stock'] == 0) ? 'null-stock' : '';

            echo "<tr class='$lowStockClass $nullStockCount'>
                     <td>{$product['products']}</td>
                <td>{$product['stock']} {$product['type_amount']}</td>
                <td>{$product['stock_min']} {$product['type_amount']}</td>
                <td>$" . number_format($product['price'], 2) . "</td>
                <td>
                    <button class='stock-table--button delete-button' data-id='{$product['stock_id']}'>
                        <span class='delete'></span>
                    </button>
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

  <div id="addProductModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Agregar Producto</h2>
        <form id="addProductForm">
            <input type="hidden" name="action" value="add">
            <input type="text" id="productName" name="productName" placeholder="Nombre del producto" required> <p></p>
            <input type="number" id="productStock" name="productStock" placeholder="Stock" required>
            <input type="number" id="productMinStock" name="productMinStock" placeholder="Stock Mínimo" required>
            <select id="productTypeAmount" name="productTypeAmount" required>
                <option value="" disabled selected>Tipo de unidad</option>
                <option value="kg.">Kilogramos</option>
                <option value="u.">Unidades</option>
            </select> <p></p>
            <input type="number" step="0.01" id="productPrice" name="productPrice" placeholder="Precio (sin signo)" required> <p></p>


            <button type="submit">Guardar</button>
        </form>
    </div>
</div>

<script>
        document.getElementById("stockWariningBtn").addEventListener("click", function() {
            const rows = document.querySelectorAll(".stock-table tbody tr");
            const isActive = this.classList.toggle("active");

            if (isActive) {
                rows.forEach(row => {
                    if (!row.classList.contains("low-stock")) {
                        row.style.display = "none";
                    } else {
                        row.style.display = "";
                    }
                });
                // Desactivar el otro botón y restablecer su filtro
                document.getElementById("stockAlertBtn").classList.remove("active");
            } else {
                rows.forEach(row => {
                    row.style.display = "";
                });
            }
        });

        document.getElementById("stockAlertBtn").addEventListener("click", function() {
            const rows = document.querySelectorAll(".stock-table tbody tr");
            const isActive = this.classList.toggle("active");

            if (isActive) {
                rows.forEach(row => {
                    if (!row.classList.contains("null-stock")) {
                        row.style.display = "none";
                    } else {
                        row.style.display = "";
                    }
                });
                // Desactivar el otro botón y restablecer su filtro
                document.getElementById("stockWariningBtn").classList.remove("active");
            } else {
                rows.forEach(row => {
                    row.style.display = "";
                });
            }
        });
    </script>

<?php

    require_once 'footer.php';

?>