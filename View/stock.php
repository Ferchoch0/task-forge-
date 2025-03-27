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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>






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
            <!-- El contenido se cargará dinámicamente con fetch() -->
          </tbody>
        </table>
      </section>
        </article>
    </div>
    
  </div>


<!-- Agregar Productos -->
  
  <div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p class="modal-title">Agregar Producto</p>
        <form id="addProductForm">
            <input type="hidden" name="action" value="add">
            <label class="label-sub-title">
                <span class="modal-sub-title">Nombre de Producto</span>
                <div class="modal-field-container">
                    <input type="text" class="modal-field" id="productName" name="productName" placeholder="Ingresar Nombre" autocomplete="off" required>
                </div>   
            </label>

            <div class="form-group-aligned">
                <label class="label-sub-title">
                    <span class="modal-sub-title">Stock</span>
                    <div class="modal-field-container">
                        <input type="number" class="modal-field" id="productStock" name="productStock" placeholder="Ingresa Cantidad" autocomplete="off" required>
                    </div>
                </label>
                <label class="label-sub-title">
                    <span class="modal-sub-title">Stock Minimo</span>
                    <div class="modal-field-container">
                        <input type="number" class="modal-field" id="productMinStock" name="productMinStock" placeholder="Ingresa Cantidad" autocomplete="off" required>
                    </div>
                </label>
            </div>

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

<!-- Editar Productos -->

<div id="editProductModal" class="modal">
    <div class="modal-content">
        <span class="close-edit close">&times;</span>
        <p class="model-title">Editar Producto</p>
        <form id="editProductForm">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="editProductId" name="editProductId">

            <label class="label-sub-title">
                <span class="modal-sub-title">Nombre de Producto</span>
                <div class="modal-field-container">
                    <input type="text" class="modal-field" id="editProductName" name="editProductName" placeholder="Nombre del producto" required>
                </div>   
            </label>


            <div class="form-group-aligned">
                <label class="label-sub-title">
                    <span class="modal-sub-title">Cantidad Actual</span>
                    <div class="modal-field-container">
                        <input type="number" class="modal-field" id="editProductStock" name="editProductStock" placeholder="Stock" required>
                    </div>   
                </label>
                <label class="label-sub-title">
                    <span class="modal-sub-title">Cantidad Minima</span>
                    <div class="modal-field-container">
                        <input type="number" class="modal-field" id="editProductMinStock" name="editProductMinStock" placeholder="Stock Mínimo" required>
                    </div>   
                </label>
            </div>

            <label class="label-sub-title">
                    <span class="modal-sub-title">Tipo de Unidad Actual</span>
                    <div class="modal-field-container">
                        <select id="editProductTypeAmount" class="modal-field" name="editProductTypeAmount" required>
                            <option value="" disabled selected>Seleccionar</option>
                            <option value="kg.">Kilogramos</option>
                            <option value="u.">Unidades</option>
                        </select>
                    </div>   
            </label>
           
            <label class="label-sub-title">
                <span class="modal-sub-title">Costo Actual</span>
                <div class="modal-field-container">
                    <span class="field-signed">$</span>
                    <input type="number" class="modal-field" step="0.01" id="editProductPrice" name="editProductPrice" placeholder="Costo (sin signo)" required>
                </div>   
            </label>

            <span class="model-separator"></span>
            
            <div class="modal-submit">
                <button type="submit" class="submit-button">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

</body>

<script src="../View/src/assets/js/stockLoading.js"></script>

<?php

    require_once 'footer.php';

?>