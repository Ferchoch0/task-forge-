
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
    <form action="../Controller/verificationController.php" method="POST" class="verification-form">
        <label for="verification-code">Código de verificación:</label> <p></p>
        <div class="verification-card-input-wrapper">
          <input class="verification-card-input" placeholder="______" maxlength="6" type="tel" id="verification-code" name="verification_code" autocomplete="off" pattern="\d*" required>
          <div class="verification-card-input-bg"></div>
        </div>
        
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("verification-code");

    // Cuando el usuario escribe, verifica si llegó a 6 dígitos y deselecciona
    input.addEventListener("input", function () {
        if (this.value.length === 6) {
            this.blur(); // Deselecciona el input
        }
    });

    // Cuando el usuario vuelve a seleccionar el input, borra el último dígito
    input.addEventListener("focus", function () {
        if (this.value.length > 0) {
            this.value = this.value.slice(0, -1); // Borra el último carácter
        }
    });
});
</script>


<?php require_once 'footer.php'; ?>
