<?php
require_once 'head.php';
require_once '../Controller/controllerLogin.php';
?>

<link rel="stylesheet" href="src/assets/css/main.css">

</head>


<body>
 

<form action="" method="post">

    <h1 class="title"> TaskForge+ </h1>

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
    <button type="submit"> Ingresar </button>
    <a href="../View/register.php">¿No tenes cuenta? Registrate aca!</a>

</form>   

<div class="e-card playing">
  <div class="image"></div>
  
  <div class="wave"></div>
  <div class="wave"></div>
  <div class="wave"></div>
</div>  

</body>
</html>