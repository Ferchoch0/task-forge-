
<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/userModel.php';
require_once 'head.php';

?>

<section class="verification-section">
    <h2>Verifica tu cuenta</h2>
    <form action="../Controller/controllerVerification.php" method="POST">
        <label for="verification-code">Código de verificación:</label>
        <input type="text" id="verification-code" name="verification_code" required>
        <button type="submit">Verificar</button>
    </form>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;">El código ingresado es incorrecto.</p>
    <?php endif; ?>
</section>


<?php require_once 'footer.php'; ?>
