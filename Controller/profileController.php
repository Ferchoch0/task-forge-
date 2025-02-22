<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/userModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $userId = $_SESSION['user_id'];
    $userModel = new UserModel($conn);

    $image = $_FILES['profileImage'];
    $imageName = time() . "_" . basename($image['name']);
    $targetDir = "../View/src/img/uploads/";
    $targetFile = $targetDir . $imageName;

    // Validar y mover el archivo
    if (move_uploaded_file($image['tmp_name'], $targetFile)) {
        if ($userModel->updateUserProfileImage($userId, $imageName)) {
            echo "<script>alert('Foto de perfil actualizada exitosamente');</script>";
            header("Location: ../View/settings.php");
        } else {
            echo "<script>alert('Error al actualizar la foto en la base de datos');</script>";
        }
    } else {
        echo "<script>alert('Error al subir el archivo');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deleteAccount'])) {
        $userId = $_SESSION['user_id'];

        $userModel = new UserModel($conn);

        if ($userModel->deleteUserAccount($userId)) {
            session_destroy();
            header("Location: ../View/goodbye.php");
            exit();
        } else {
            echo "Error al eliminar la cuenta. Intenta de nuevo.";
        }
    }
}
?>

