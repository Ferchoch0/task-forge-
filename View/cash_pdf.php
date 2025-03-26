<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/balanceModel.php';
require_once '../Model/userModel.php';
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['user_id'])) {
    die("Acceso denegado.");
}

$balanceModel = new BalanceModel($conn);
$userModel = new UserModel($conn);
$userId = $_SESSION['user_id'];

if (!$userModel->isEmailVerified($userId)) {
    die("Acceso denegado.");
}

$transactions = $balanceModel->getUserTransaction($userId);
$username = $_SESSION['username'];

// Configurar DOMPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// Iniciar buffer de salida
ob_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cierre de Caja</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Cierre de Caja</h1>
    <p>Usuario: <?php echo htmlspecialchars($username); ?></p>
    <p>Fecha: <?php echo date("Y-m-d"); ?></p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Movimiento</th>
                <th>Monto</th>
                <th>Método</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalEfectivo = 0;
            $totalTarjeta = 0;
            $totalTransferencia = 0;
            $totalGeneral = 0;

            if ($transactions) {
                foreach ($transactions as $transaction) {
                    if ($transaction['payment'] === 'deuda') {
                        continue;
                    }

                    if ($transaction['source'] === 'buy') {
                        $type = 'Retiro';
                        $amount = $transaction['amount'] * $transaction['price'];
                    } elseif ($transaction['source'] === 'sell') {
                        $type = 'Ingreso';
                        $amount = $transaction['amount'] * $transaction['price'];
                    } elseif ($transaction['source'] === 'cash') {
                        $type = ($transaction['mov_type'] == 1) ? 'Ingreso' : 'Retiro';
                        $amount = $transaction['price'];
                    } else {
                        $type = 'Desconocido';
                        $amount = 0;
                    }

                    if ($transaction['payment'] === 'Efectivo') {
                        $totalEfectivo += ($type === 'Ingreso') ? $amount : -$amount;
                    } elseif ($transaction['payment'] === 'Tarjeta') {
                        $totalTarjeta += ($type === 'Ingreso') ? $amount : -$amount;
                    } elseif ($transaction['payment'] === 'Transferencia') {
                        $totalTransferencia += ($type === 'Ingreso') ? $amount : -$amount;
                    }

                    $totalGeneral += ($type === 'Ingreso') ? $amount : -$amount;

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($transaction['products']) . "</td>";
                    echo "<td>$type</td>";
                    echo "<td>$" . number_format($amount, 2) . "</td>";
                    echo "<td>" . htmlspecialchars($transaction['payment']) . "</td>";
                    echo "<td>" . htmlspecialchars($transaction['date']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay transacciones registradas</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <h2>Resumen</h2>
    <table>
        <tr>
            <th>Efectivo</th>
            <th>Tarjeta</th>
            <th>Transferencia</th>
            <th>Total</th>
        </tr>
        <tr>
            <td>$<?php echo number_format($totalEfectivo, 2); ?></td>
            <td>$<?php echo number_format($totalTarjeta, 2); ?></td>
            <td>$<?php echo number_format($totalTransferencia, 2); ?></td>
            <td>$<?php echo number_format($totalGeneral, 2); ?></td>
        </tr>
    </table>
</body>
</html>

<?php
$html = ob_get_clean();

// Generar el PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Enviar PDF al navegador
$dompdf->stream("cierre_caja.pdf", ["Attachment" => false]);
?>
