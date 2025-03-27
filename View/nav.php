<?php
$current_page = basename($_SERVER['PHP_SELF']); // Obtiene el nombre del archivo actual
?> 
 
 <div id="preloader">
    <div class="spinner"></div>
  </div>

<?php
  $margin = 0.30;
  $iva = 0.21;
?>

  <nav>


    <section class="navbar-container">   
      <div class="navbar-logo">
        <span class="welcome"></span>
      </div>   
          <div class="navbar-user--info">
            <h3>Buenos Dias, </h3>
            <h1>  <?php echo htmlspecialchars($username); ?> </h1>
          </div>
    </section>

    <div class="menu-container">

    <div class="separator"></div>

    <section class="navbar-menu">
          <a href="../View/balance.php" class="<?= ($current_page == 'balance.php') ? 'nav-active' : '' ?> narbar-menu--option"><span class="nav-icon balance-nav"></span>Balance</a>
          <a href="../View/stock.php" class="<?= ($current_page == 'stock.php') ? 'nav-active' : '' ?> narbar-menu--option"><span class="nav-icon stock-nav"></span>Stock</a>
          <a href="../View/sell.php" class="<?= ($current_page == 'sell.php') ? 'nav-active' : '' ?> narbar-menu--option"><span class="nav-icon sell-nav"></span>Ventas</a>
          <a href="../View/buy.php" class="<?= ($current_page == 'buy.php') ? 'nav-active' : '' ?> narbar-menu--option"><span class="nav-icon buy-nav"></span>Compras</a>
          <a href="../View/cash.php" class="<?= ($current_page == 'cash.php') ? 'nav-active' : '' ?> narbar-menu--option"><span class="nav-icon cash-nav"></span>Caja</a>          
    </section>
    
    <div class="separator"></div>

    <span class="navbar-menu-title">Personas</span>

    <section class="navbar-menu">
          <a href="../View/client.php" class="<?= ($current_page == 'client.php') ? 'nav-active' : '' ?> narbar-menu--option"><span class="nav-icon client-nav"></span>Clientes</a>
          <a href="../View/invoice.php" class="<?= ($current_page == 'invoice.php') ? 'nav-active' : '' ?> narbar-menu--option"><span class="nav-icon invoice-nav"></span>Facturas</a>
          <a href="../View/supplier.php" class="<?= ($current_page == 'supplier.php') ? 'nav-active' : '' ?> narbar-menu--option"><span class="nav-icon supplier-nav"></span>Proveedores</a>
    </section>
    
    <div class="separator"></div>

    <span class="navbar-menu-title">Sesion</span>

    <section class="navbar-menu">
          <a class="<?= ($current_page == 'business.php') ? 'nav-active' : '' ?> narbar-menu--option"><span class="nav-icon mybusiness-nav"></span>Mi negocio</a>
          <a href="../View/profile.php" class="<?= ($current_page == 'profile.php') ? 'nav-active' : '' ?> narbar-menu--option"><span class="nav-icon mysesion-nav"></span>Mi Cuenta</a>
          <a href="../View/settings.php" class="<?= ($current_page == 'settings.php') ? 'nav-active' : '' ?> narbar-menu--option"><span class="nav-icon settings-nav"></span>Settings</a>
    </section>
    </div>

    <div class="separator"></div>


    <section class="navbar-logout">
      <a href="../View/logout.php" class="navbar-logout--button"><span class="nav-icon close-sesion"></span>Cerrar Sesión</a>
    </section>


      
  </nav>