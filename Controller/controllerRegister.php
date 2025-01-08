<?php 

require_once '../Model/connection.php';
require_once '../Model/userModel.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Instancia de la conexión
    $userModel = new UserModel($conn);

    // Verificar si el correo ya existe
    if ($userModel->emailExists($email)) {
        echo "<script>alert('El correo electrónico ya está registrado');</script>";
    } else {
        // Si el correo no existe, registrar al usuario
        if ($userModel->registerUser($username, $email, $password)) {
            echo "<script>alert('Usuario registrado con éxito');</script>";
            header("Location: ../View/success.php"); // Redirige a una página de éxito
        } else {
            echo "<script>alert('Error al registrar el usuario');</script>";
        }
    }
}

?>