<?php
require_once '../Model/connection.php';
require_once '../Model/userModel.php';

// Instancia del modelo
$userModel = new UserModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($userModel->emailExists($email)) {
        echo "<script>alert('El correo electrónico ya está registrado');</script>";
    } else {
        // Registrar usuario y obtener el código de verificación
        $verificationCode = $userModel->registerUser($username, $email, $password);

        if ($verificationCode !== false) {
            // Enviar el correo con el código de verificación
            $subject = "Código de verificación";
            $message = "Tu código de verificación es: $verificationCode";
            $headers = "From: no-reply@tuweb.com";

            if (mail($email, $subject, $message, $headers)) {
                // Redirigir a la página de verificación
                header("Location: ../View/verification.php");
                exit();
            } else {
                echo "<script>alert('Error al enviar el correo de verificación');</script>";
            }
        } else {
            echo "<script>alert('Error al registrar el usuario');</script>";
        }
    }
}
?>
