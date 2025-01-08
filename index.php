<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: View/login.php");
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anon';

require_once './View/head.php'
?>

<link rel="stylesheet" href="View/src/assets/css/global.css">
<link rel="stylesheet" href="View/src/assets/css/main.css">
<link rel="stylesheet" href="View/src/assets/css/icon.css">




</head>


<body>
  <div id="preloader">
    <div class="spinner"></div>
  </div>

  <nav>
    <div class="navbar-container">
      <span class="user--plus icon"></span>
      <div class="navbar-user--info">
        <h3>Buenos Dias, </h3>
        <h1>  <?php echo htmlspecialchars($username); ?> </h1>
      </div>  
    </div>

    <a href="View/logout.php">Cerrar sesión</a>
  </nav>

  
    <!-- Resto del contenido del dashboard -->

</body>
<script src="View/src/assets/js/script.js"></script>

</html>
