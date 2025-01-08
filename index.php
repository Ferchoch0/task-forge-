<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: View/login.php");
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anon';

require_once './View/head.php'
?>

  
  </head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    <!-- Resto del contenido del dashboard -->
    <a href="View/logout.php">Cerrar sesión</a>
</body>
</html>