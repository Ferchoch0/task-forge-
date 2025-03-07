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

<form action="../Controller/loginController.php" method="post">

    <div class="title-container">
        <span class="logo--otter icon"> </span> 
        <h1 class="title">  OtterTask+ </h1>
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


<?php

require_once 'footer.php';

?>