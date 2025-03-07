<?php
require_once 'head.php';
?>

<link rel="stylesheet" href="src/assets/css/sesion.css">
<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/icon.css">



</head>


<body>

<div id="preloader">
    <div class="spinner"></div>
  </div>

<div class="e-card playing">
  <div class="image"></div>
  
  <div class="wave"></div>
  <div class="wave"></div>
  <div class="wave"></div>
</div>   

<form action="../Controller/registerController.php" method="post">

    <div class="title-container">
        <span class="logo--otter icon"> </span> 
        <h1 class="title">  OtterTask+ </h1>
    </div>

      <!-- Mensaje de error -->
    <?php
    session_start();
    if (isset($_SESSION['error'])) {
        echo "<div class='error-message'>" . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']);
    }
    ?>

    <div class="input-container">
        <input placeholder="" type="text" class="input" id="username" name="username" required>
        <div class="cut cut-short"></div>
        <label class="iLabel" for="username">Name</label>
    </div>

    <div class="input-container">
        <input placeholder="" type="email" class="input" id="email" name="email" required>
        <div class="cut cut-short"></div>
        <label class="iLabel" for="email">Email</label>
    </div>

    <div class="input-container">
        <input placeholder="" type="password" class="input" id="password" name="password" required>
        <div class="cut"></div>
        <label class="iLabel" for="password">Password</label>
    </div>
    <button type="submit"> Registrarse </button>
    <a href="../View/login.php">¿Ya tenes cuenta? Ingresa aca!</a>

</form>   

</body>

<?php

require_once 'footer.php';

?>