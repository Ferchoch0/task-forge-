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
$mail = $_SESSION['email'];
$imagePath = "../View/src/img/uploads/default-profile.png";

$userModel = new UserModel($conn);
$imageFromDB = $userModel->getUserProfileImage($userId);

if ($imageFromDB) {
    $imagePath = "../View/src/img/uploads/" . $imageFromDB;
}

?>


<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/icon.css">
<link rel="stylesheet" href="src/assets/css/profile.css">





</head>


<body>

  <?php require_once 'nav.php'; ?>

  
    <!-- Resto del contenido del dashboard -->
  <div class="dashboard-container">
    <div class="dashboard-container--ajust">
    <article class="background">

      <!-- <span class="logo--otter icon--background"></span> -->
    </article>
    
    <article class="profile">
        <section class="profile-container">
        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Foto de perfil" class="profile-img" width="150">
          <h1>  <?php echo htmlspecialchars($username); ?>  </h1>
          <h3>  <?php echo htmlspecialchars($mail); ?>  </h3>
        </section>
      
    </article>


    <article class="feed">
      <section class="feed-menu">
        <div class="feed-menu--ajust">
          <button class="feed-menu--btn">Nuevo</button>
        </div>
      </section>

    </article>
    </div>
  </div>
</body>
    
<?php

require_once 'footer.php';

?>