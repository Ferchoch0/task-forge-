
<?php
session_start();



require_once '../Model/connection.php';
require_once '../Model/userModel.php';
require_once 'head.php';


?>

<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/main.css">
<link rel="stylesheet" href="src/assets/css/verification.css">
<link rel="stylesheet" href="src/assets/css/icon.css">

</head>


<body>
<div id="preloader">
    <div class="spinner"></div>
</div>


<section class="verification-section">
    <span class="logo--otter icon"> </span> 
    <h2>Verifica tu cuenta</h2>
    <form action="../Controller/verificationController.php" method="POST">
        <label for="verification-code">Código de verificación:</label>
        <input type="text" id="verification-code" name="verification_code" autocomplete="off" pattern="\d*" required>
        
        <h6>Se envio un mail con el codigo a el correo ingresado</h6>

        <div class="verification-button-container">
            <button type="submit" class="verification-button">Verificar</button>
        </div>
    </form>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;">El código ingresado es incorrecto.</p>
    <?php endif; ?>
</section>

</body>


<?php require_once 'footer.php'; ?>
