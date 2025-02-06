<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

require_once 'head.php';
require_once '../Model/userModel.php';
require_once '../Model/connection.php';


$username = $_SESSION['username'];
$userId = $_SESSION['user_id'];
$imagePath = "../View/src/img/uploads/default-profile.png";

$userModel = new UserModel($conn);
$imageFromDB = $userModel->getUserProfileImage($userId);

if ($imageFromDB) {
    $imagePath = "../View/src/img/uploads/" . $imageFromDB;
}

?>


<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/main.css">
<link rel="stylesheet" href="src/assets/css/profile.css">
<link rel="stylesheet" href="src/assets/css/icon.css">




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
          <a href="#" class="value">
            <span class="profile--user"></span> Perfil
          </a>
            
          <a href="../View/settings.php" class="value">
            <span class="ajust--user"></span> Ajustes
          </a>

          <a href="../View/logout.php" class="value">
            <span class="sesion--xmark"></span> Cerrar sesión
          </a>
        </div>
      </div>
      
    </div>
  </nav>

  
    <!-- Resto del contenido del dashboard -->
  <div>
    <article class="background">

      <!-- <span class="logo--otter icon--background"></span> -->
    </article>
    
    <article class="profile">
        <section class="profile-container">
        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Foto de perfil" class="profile-img" width="150">
          <h1>  <?php echo htmlspecialchars($username); ?>  </h1>
          <h3>
              <a>grupos</a>
              <a>contactos</a>
          </h3>
        </section>
      
    </article>


    <article class="feed">
      <section class="feed-menu">
        <div class="feed-menu--ajust">
          <button class="feed-menu--btn">Nuevo</button>
        </div>
      </section>

      <section class="feed-groups">
        <h2 class="feed-groups--title">Grupos</h2>
          <div class="feed-groups--group">
            <h3>titulo</h3>
            <h6>descripcion</h6>
          </div>

          <div class="feed-groups--group">
            <h3>titulo</h3>
            <h6>descripcion</h6>
          </div>

          <div class="feed-groups--group">
            <h3>titulo</h3>
            <h6>descripcion</h6>
          </div>
      </section>

    </article>
</div>
    
<?php

require_once 'footer.php';

?>