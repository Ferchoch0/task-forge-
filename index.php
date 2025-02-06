<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: View/login.php");
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anon';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="View/src/img/logo.svg" />
    <title>OtterTask: Principal</title>

<link rel="stylesheet" href="View/src/assets/css/global.css">
<link rel="stylesheet" href="View/src/assets/css/main.css">
<link rel="stylesheet" href="View/src/assets/css/icon.css">




</head>


<body>
  <div id="preloader">
    <div class="spinner"></div>
  </div>

  <nav>

    <span class="logo--otter icon"></span>

    <div class="navbar-container">      
      <div class="input">
        <button class="value-toggle">
          <span class="user--plus icon"></span>
          <div class="navbar-user--info">
            <h3>Buenos Dias, </h3>
            <h1>  <?php echo htmlspecialchars($username); ?> </h1>
          </div>
        </button>
        
        <div class="value-menu">
          <a href="View/profile.php" class="value">
            <span class="profile--user"></span> Perfil
          </a>
          <button class="value">
          <span class="ajust--user"></span> Ajustes
          </button>
          <a href="View/logout.php" class="value">
          <span class="sesion--xmark"></span> Cerrar sesión
          </a>
        </div>
      </div>
      
    </div>
  </nav>

  
    <!-- Resto del contenido del dashboard -->

</body>
<script src="View/src/assets/js/dropDown.js"></script>
<script src="View/src/assets/js/preLoad.js"></script>



</html>
