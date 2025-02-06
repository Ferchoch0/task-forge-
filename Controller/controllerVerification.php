<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/userModel.php';

$userModel = new UserModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verification_code'])) {
    $userId = $_SESSION['user_id'];
    $verificationCode = $_POST['verification_code'];

    if ($userModel->verifyCode($userId, $verificationCode)) {
        echo "<script>alert('Cuenta verificada con éxito');</script>";
        header("Location: ../View/profile.php");
        exit();
    } else {
        echo "<script>alert('Código incorrecto. Intenta nuevamente.');</script>";
        header("Location: ../View/verification.php?error=1");
    }
}
?>
