<?php
require_once 'head.php';
require_once '../Controller/controllerRegister.php';
?>

<link rel="stylesheet" href="src/assets/css/main.css">

</head>


<body>
<div class="e-card playing">
  <div class="image"></div>
  
  <div class="wave"></div>
  <div class="wave"></div>
  <div class="wave"></div>
</div>   

<form action="" method="post">

    <h1 class="title"> TaskForge+ </h1>

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
</html>