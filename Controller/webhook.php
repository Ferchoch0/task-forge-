<?php
require_once '../Model/connection.php';
require_once '../Model/businessModel.php';

$envPath = realpath('../.env'); 
if ($envPath && file_exists($envPath)) {
    $envFile = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envFile as $line) {
        putenv(trim($line));
    }
}

// Log para debug
file_put_contents("../log_webhook.txt", date("Y-m-d H:i:s") . " - Webhook recibido:\n" . file_get_contents("php://input") . "\n\n", FILE_APPEND);

$body = file_get_contents("php://input");
$data = json_decode($body, true);

if (!$data) {
    http_response_code(400);
    echo "Datos no válidos";
    exit;
}

// Si viene el campo type y es 'payment'
if (isset($data['type']) && $data['type'] === 'payment' && isset($data['data']['id'])) {
    $paymentId = $data['data']['id'];
}
// Si viene solo topic = 'payment' y resource con el id
elseif (isset($data['topic']) && $data['topic'] === 'payment' && isset($data['resource'])) {
    $paymentId = is_numeric($data['resource']) ? $data['resource'] : null;
} else {
    // No es un evento que nos interese
    http_response_code(200);
    echo "Evento ignorado";
    exit;
}

if (!$paymentId) {
    http_response_code(400);
    echo "No se pudo obtener el ID del pago";
    exit;
}

// Ahora consultamos a la API de Mercado Pago con ese ID
$access_token = getenv('SMTP_ACCESS_TOKEN');

$ch = curl_init("https://api.mercadopago.com/v1/payments/$paymentId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $access_token"
]);
$response = curl_exec($ch);
curl_close($ch);

$paymentInfo = json_decode($response, true);

// Log para saber qué devuelve la API de MP
file_put_contents("../log_webhook.txt", date("Y-m-d H:i:s") . " - Respuesta de la API de MP:\n" . $response . "\n\n", FILE_APPEND);

if ($paymentInfo && isset($paymentInfo['status']) && $paymentInfo['status'] === 'approved') {
    $businessId = $paymentInfo['metadata']['business_id'] ?? null;

    if ($businessId) {
        $businessModel = new BusinessModel($conn);
        $updated = $businessModel->updateSubscriptionStatus($businessId);
        
        if ($updated) {
            file_put_contents("../log_webhook.txt", date("Y-m-d H:i:s") . " - Subscripción actualizada para business_id $businessId\n\n", FILE_APPEND);
        } else {
            file_put_contents("../log_webhook.txt", date("Y-m-d H:i:s") . " - Falló la actualización para business_id $businessId\n\n", FILE_APPEND);
        }
    } else {
        file_put_contents("../log_webhook.txt", date("Y-m-d H:i:s") . " - No se encontró business_id en metadata\n\n", FILE_APPEND);
    }
}

http_response_code(200);
echo "OK";
