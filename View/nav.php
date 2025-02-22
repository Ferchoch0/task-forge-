  <div id="preloader">
    <div class="spinner"></div>
  </div>

  <nav>


    <section class="navbar-container">      
          <div class="navbar-user--info">
            <h3>Buenos Dias, </h3>
            <h1>  <?php echo htmlspecialchars($username); ?> </h1>
          </div>
    </section>

    <div class="separator"></div>


        <section class="navbar-menu">
          <a href="../View/stock.php" class="narbar-menu--option">Stock</a>
          <a href="../View/sell.php" class="narbar-menu--option">Ventas</a>
          <a href="../View/buy.php" class="narbar-menu--option">Compras</a>
          <a class="narbar-menu--option">Clientes</a>
          <a class="narbar-menu--option">Gastos</a>
          <a class="narbar-menu--option">Proveedores</a>
          <a class="narbar-menu--option">Productos</a>
          <a class="narbar-menu--option">Balance</a>
          <a class="narbar-menu--option">Utilidades</a>
          <a href="../View/cash.php" class="narbar-menu--option">Caja</a>          
          <a class="narbar-menu--option">Mi negocio</a>
          <a href="../View/profile.php" class="narbar-menu--option">Mi Cuenta</a>
          <a href="../View/settings.php" class="narbar-menu--option">Configuración</a>
        </section>
    
    <div class="separator"></div>

    <section class="navbar-logout">
      <a href="../View/logout.php" class="navbar-logout--button">Cerrar Sesión</a>
    </section>


      
  </nav>