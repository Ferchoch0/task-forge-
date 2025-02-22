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
$imagePath = "../View/src/img/uploads/default-profile.png";  // Imagen por defecto

$userModel = new UserModel($conn);
$imageFromDB = $userModel->getUserProfileImage($userId);

// Si el usuario tiene una imagen, cambia la ruta
if ($imageFromDB) {
    $imagePath = "../View/src/img/uploads/" . $imageFromDB;
}

?>


<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/icon.css">
<link rel="stylesheet" href="src/assets/css/settings.css">





</head>


<body>

<?php require_once 'nav.php'; ?>


    <!-- Resto del contenido del dashboard -->
    <div class="settings">
    <div class="settings-container">
        <article class="settings-menu">
              <h1>Ajustes</h1>
            
              <div class="radio-input-wrapper">
                <label class="settings-menu--label">
                  <input value="value-1" name="value-radio" id="value-1" class="settings-menu--radio-input" type="radio" checked>
                  <div class="settings-menu--radio-design"></div>
                  <div class="settings-menu--text">Perfil</div>
                </label>
  
                <label class="settings-menu--label">
                  <input value="value-2" name="value-radio" id="value-2" class="settings-menu--radio-input" type="radio">
                  <div class="settings-menu--radio-design"></div>
                  <div class="settings-menu--text">Configuración</div>
                </label>
        </article>

        <article class="settings-options">
            <section class="settings-options--profile" id="content-profile">
              <div class="settings-options-title">
                <h1>Perfil</h1>
              </div>

              <hr class="separator">

              <div class="settings-options--content">

                <div class="settings-options--content-img">
                  <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Foto de perfil" class="profile-img" width="150">
                  <div class="settings-options--custom-file">
                    <label for="profileImage" class="custom-file-upload">
                      <span class="edit--user"></span>
                    </label>
                  </div>                  

                </div>

                <form action="../Controller/profileController.php" method="POST" enctype="multipart/form-data">
                  <input type="file" class="settings-options-upload" id="profileImage" name="profileImage" accept="image/*" required>
                  <div class="settings-options-btncontainer">
                    <button type="submit" class="settings-options-save">
                      <span class="settings-options--icon">
                        <svg viewBox="0 0 384 512" height="0.9em" class="icon">
                          <path
                            d="M0 48V487.7C0 501.1 10.9 512 24.3 512c5 0 9.9-1.5 14-4.4L192 400 345.7 507.6c4.1 2.9 9 4.4 14 4.4c13.4 0 24.3-10.9 24.3-24.3V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z"
                          ></path>
                        </svg>
                      </span>
                      <p class="settings-options--btntext">Guardar</p>
                    </button>
                  </div>
                </form>
                <hr class="separator">
              </div>
            </section>


            <section class="settings-options--config" id="content-config">
              <div class="settings-options-title">
                <h1>Configuración</h1>
              </div>

              <hr class="separator">

              <div class="settings-options--config-content">

                <a href="../View/logout.php" class="settings-options-save">
                  <span class="settings-options--icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="35" height="25" viewBox="0 0 512 512"><path fill="#181818" d="M256 512a256 256 0 1 0 0-512a256 256 0 1 0 0 512m-81-337c9.4-9.4 24.6-9.4 33.9 0l47 47l47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47l47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47l-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47l-47-47c-9.4-9.4-9.4-24.6 0-33.9"/></svg>
                  </span>
                  <p class="settings-options--btntext">Cerrar Sesion</p>
                </a>

              <form action="../Controller/profileController.php" method="POST">
                <button type="submit" name="deleteAccount" class="settings-options-save">
                  <span class="settings-options--icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="25" viewBox="0 0 128 512"><path fill="#181818" d="M96 64c0-17.7-14.3-32-32-32S32 46.3 32 64v256c0 17.7 14.3 32 32 32s32-14.3 32-32zM64 480a40 40 0 1 0 0-80a40 40 0 1 0 0 80"/></svg>
                  </span>
                  <p class="settings-options--btntext">Borrar Cuenta</p>
                </button>
              </form>
              </div>
              
              <hr class="separator">
            </section>
        </article>
    </div>

    </div>




    <?php

require_once 'footer.php';

?>