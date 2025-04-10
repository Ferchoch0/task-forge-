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

                <h1>Ventas</h1>

                <section class="stock-menu">
                    <div class="stock-menu--options">
                        <button class="menu--button">
                            <span class="add icon"></span>
                            <span>Agregar venta</span>
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
                                <th>Precio total</th>
                                <th>Factura</th>
                                <th>Fecha</th>
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

    <div id="addModal" class="modal"> 
        <div class="modal-content little">
            <span class="close">&times;</span>
            <p class="modal-title">Agregar Venta</p>
            <form id="addSellForm" method="POST">
                <input type="hidden" name="action" value="addSell">
                <div class="form-group">
                    <label class="label-sub-title">
                        <span class="modal-sub-title">Producto</span>
                        <div class="modal-field-container">
                            <select name="product_id" class="modal-field" id="product">
                                <?php
                                    $products = $stockModel->getUserProducts($userId); // Obtener productos del usuario
                                        foreach ($products as $product) {
                                            echo "<option value='{$product['stock_id']}' data-price='{$product['price']}'> {$product['products']} </option>";
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
                            <input type="text" class="modal-field" id="priceSell" name="priceSell" placeholder="Ingresar Precio" autocomplete="off" readonly>

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
                            <td  class="change-table--footer" id="totalSell"></td>
                        </tr>
                    </tbody>
                </table>

                <div class="invoice-group--checkbox">
                    <input type="checkbox" name="invoice" id="invoice" value="off" class="modal-checkbox">
                    <label for="invoice">Factura</label>
                </div>

                <div id="invoiceGroup"  class="invoice-group check-group">
                    <label class="label-sub-title">
                        <span class="modal-sub-title">Facturas Guardadas</span>
                        <div class="modal-field-container">
                            <select name="name" class="modal-field select-client" id="name">
                                
                            </select>
                        </div>
                    </label>
                    
                    <button type="button" id="addNewBusiness" class="submit-button small-submit submenu--button"><span class="plus-button"></span></button>
                
                </div>

                
                <label class="label-sub-title">
                    <span class="modal-sub-title">Metodo de pago</span>
                    <div class="modal-field-container small-field">
                        <select name="payment" class="modal-field" id="payment">
                            <option value="" disabled selected>Seleccionar</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="deuda" disabled>Deuda</option>
                        </select>
                    </div>   
                </label>

                <span class="model-separator"></span>
                
                <div class="modal-submit">
                    <button type="submit" class="submit-button">Registrar Venta</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addSubModal-1" class="modal"> 
        <div class="modal-content">
            <span class="close--sub">&times;</span>
            <p class="modal-title">Agregar Factura</p>
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
                            <select name="typeInvoice" class="modal-field" id="typeInvoice" class="product" required>
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

    </body>

    <script src="../View/src/assets/js/sellLoading.js"></script>
    <script src="../View/src/assets/js/clientLoading.js"></script>


    <?php

    require_once 'footer.php';

    ?>