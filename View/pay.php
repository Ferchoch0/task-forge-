<?php
session_start();
require_once '../Model/connection.php';
require_once 'head.php';


$envPath = realpath('../.env'); 
if ($envPath && file_exists($envPath)) {
    $envFile = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envFile as $line) {
        putenv(trim($line));
    }
}

$access_token = getenv('SMTP_ACCESS_TOKEN'); 

$preferenceData = [
    "items" => [[
        "title" => "Suscripción mensual - Plan Básico",
        "quantity" => 1,
        "currency_id" => "ARS",
        "unit_price" => 10.00
    ]],
    "back_urls" => [
        "success" => "https://f22a-2800-810-535-336-4996-5317-89a5-e448.ngrok-free.app/taskforge+/View/success.php",
        "failure" => "...",
        "pending" => "..."
    ],
    "auto_return" => "approved",
    "notification_url" => "https://f22a-2800-810-535-336-4996-5317-89a5-e448.ngrok-free.app/taskforge+/Controller/webhook.php", // tu ngrok url
    "metadata" => [
    "business_id" => $_SESSION['business_id'] ?? 0
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/checkout/preferences');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preferenceData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $access_token"
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
$paymentUrl = $result['init_point'] ?? null;
?>

<link rel="stylesheet" href="src/assets/css/sesion.css">
<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/icon.css">
</head>
<body>

<?php require_once 'navSesion.php'; ?>

<div class="income--business-container">

    <?php if ($paymentUrl): ?>
        <div style="text-align:center; padding: 30px;">
            <h2>Confirmá tu suscripción</h2>
            <p>Plan Básico - $100/mes</p>
            <a href="<?= $paymentUrl ?>" target="_blank" class="btn btn-primary">Pagar con Mercado Pago</a>
        </div>
    <?php else: ?>
        <div class="error">No se pudo generar el link de pago.</div>
    <?php endif; ?>

</div>

</body>
<?php require_once 'footer.php'; ?>
