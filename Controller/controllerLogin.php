<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/userModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userModel = new UserModel($conn);

    if ($userModel->emailExists($email)) {
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $username, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            header("Location: ../index.php");
        } else {
            echo "<script>alert('Contraseña incorrecta');</script>";
        }
    } else {
        echo "<script>alert('El correo no está registrado');</script>";
    }
}
?>
