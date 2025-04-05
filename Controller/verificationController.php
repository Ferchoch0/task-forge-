<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/userModel.php';

// Instancia del modelo
$userModel = new UserModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $verificationCode = $_POST['verification_code'];
    $userId = $_SESSION['user_id'];

    if ($userModel->verifyUser($userId, $verificationCode)) {
        echo "<script>alert('Cuenta verificada exitosamente');</script>";
        session_write_close();
        header("Location: ../View/business.php");
        exit();
    } else {
        session_write_close();
        header("Location: ../View/verification.php?error=1");
        exit();
    }
}
?>
