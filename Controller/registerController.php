<?php
session_start();

require_once '../Model/connection.php';
require_once '../Model/userModel.php';

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$envFile = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($envFile as $line) {
    putenv(trim($line));
}

// Instancia del modelo
$userModel = new UserModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($userModel->emailExists($email)) {
        $_SESSION['error'] = "El correo electrónico ya está registrado";
        header("Location: ../View/register.php");
        exit();
    } else {
        // Registrar usuario y obtener el código de verificación
        $verificationCode = $userModel->registerUser($username, $email, $password);

        if ($verificationCode !== false) {

            $_SESSION['user_id'] = $userModel->getUserIdByEmail($email);
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
                $mail->SMTPAuth = true;
                $mail->Username = 'ottertask606@gmail.com'; // Tu correo de Gmail
                $mail->Password = getenv('SMTP_PASSWORD');
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar STARTTLS
                $mail->Port = 587; // Puerto SMTP para STARTTLS

                $mail->setFrom('no-reply@tuweb.com', 'OtterTask');
                $mail->addAddress($email, $username);

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Verificacion de cuenta - OTTER-TASK';
                $mail->Body    = "
                    <html>
                    <head>
                        <style>
                            .email-container {
                                font-family: Arial, sans-serif;
                                line-height: 1.6;
                                color: #333;
                            }
                            .email-header {
                                background-color: #7e4ef8;
                                color: white;
                                padding: 10px;
                                text-align: center;
                                font-size: 24px;
                            }
                            .email-body {
                                padding: 20px;
                            }
                            .email-footer {
                                background-color: #f5f5f5;
                                padding: 10px;
                                text-align: center;
                                font-size: 12px;
                                color: #777;
                            }
                            .verification-code {
                                font-size: 20px;
                                font-weight: bold;
                                color: #7e4ef8;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='email-container'>
                            <div class='email-header'>
                                OTTER-TASK
                            </div>
                            <div class='email-body'>
                                <p>Hola, $username,</p>
                                <p>Gracias por registrarte en OtterTask. Para completar tu registro, por favor utiliza el siguiente código de verificación:</p>
                                <p class='verification-code'>$verificationCode</p>
                                <p>Si no solicitaste esta cuenta, puedes ignorar este correo.</p>
                                <p>Gracias,</p>
                                <p>El equipo de OtterTask</p>
                            </div>
                            <div class='email-footer'>
                                &copy; " . date('Y') . " OtterTask. Todos los derechos reservados.
                            </div>
                        </div>
                    </body>
                    </html>";
                $mail->AltBody = "Hola, $username,\n\nGracias por registrarte en OtterTask. Para completar tu registro, por favor utiliza el siguiente código de verificación:\n\n$verificationCode\n\nSi no solicitaste esta cuenta, puedes ignorar este correo.\n\nGracias,\nEl equipo de OtterTask";


                $mail->send();
                // Redirigir a la página de verificación
                header("Location: ../View/verification.php");
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = "Error al enviar el correo de verificación: {$mail->ErrorInfo}";
                header("Location: ../View/register.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Error al registrar el usuario";
            header("Location: ../View/register.php");
            exit();
        }
}
} else {
    header("Location: ../View/register.php");
    exit();
}
?>
