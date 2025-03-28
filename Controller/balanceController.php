<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/BalanceModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

$userId = $_SESSION['user_id'];


$model = new BalanceModel($conn);

$dataToday = $model->getBalanceToday($userId);
$dataWeek = $model->getBalanceWeek($userId);
$dataHourly = $model->getSalesHourToday($userId);


$result = [
    'daily' => ['fechas' => [], 'ingresos' => [], 'egresos' => []],
    'weekly' => ['fechas' => [], 'ingresos' => [], 'egresos' => []]
];



// Procesar ventas y compras del balance de hoy
foreach ($dataToday['ventas'] as $venta) {
    $result['daily']['fechas'][] = $venta['fecha'];
    $result['daily']['ingresos'][] = $venta['total'];
}

foreach ($dataToday['compras'] as $compra) {
    $index = array_search($compra['fecha'], $result['daily']['fechas']);
    if ($index !== false) {
        $result['daily']['egresos'][$index] = $compra['total'];
    } else {
        $result['daily']['fechas'][] = $compra['fecha'];
        $result['daily']['ingresos'][] = 0;
        $result['daily']['egresos'][] = $compra['total'];
    }
}

// Procesar ventas y compras del balance semanal
foreach ($dataWeek['ventas'] as $venta) {
    $result['weekly']['fechas'][] = $venta['fecha'];
    $result['weekly']['ingresos'][] = $venta['total'];
}

foreach ($dataWeek['compras'] as $compra) {
    $index = array_search($compra['fecha'], $result['weekly']['fechas']);
    if ($index !== false) {
        $result['weekly']['egresos'][$index] = $compra['total'];
    } else {
        $result['weekly']['fechas'][] = $compra['fecha'];
        $result['weekly']['ingresos'][] = 0;
        $result['weekly']['egresos'][] = $compra['total'];
    }
    
}

foreach ($dataHourly as $hourlySale) {
    $result['hourly']['horas'][] = $hourlySale['hora'];  // Hora
    $result['hourly']['ingresos'][] = $hourlySale['total_venta'];  // Total de ventas por hora
}

foreach ($dataWeek['ventas'] as $venta) {
    $result['weekly_sales_only']['fechas'][] = $venta['fecha'];
    $result['weekly_sales_only']['ingresos'][] = $venta['total'];
}

if (empty($result['daily']['fechas'])) {
    $hoy = date("Y-m-d"); 
    $result['daily']['fechas'][] = $hoy;
    $result['daily']['ingresos'][] = 0;
    $result['daily']['egresos'][] = 0;
}

// Si no hay datos semanales, agregar la última semana con valores en cero
if (empty($result['weekly']['fechas'])) {
    for ($i = 6; $i >= 0; $i--) {
        $fechaSemana = date("Y-m-d", strtotime("-$i days"));
        $result['weekly']['fechas'][] = $fechaSemana;
        $result['weekly']['ingresos'][] = 0;
        $result['weekly']['egresos'][] = 0;
    }
}

// Si no hay datos de ventas semanales sin egresos
if (empty($result['weekly_sales_only']['fechas'])) {
    for ($i = 6; $i >= 0; $i--) {
        $fechaSemana = date("Y-m-d", strtotime("-$i days"));
        $result['weekly_sales_only']['fechas'][] = $fechaSemana;
        $result['weekly_sales_only']['ingresos'][] = 0;
    }
}

// Si no hay datos por hora, agregar un rango de 24 horas con valores en cero
if (empty($result['hourly']['horas'])) {
    for ($h = 0; $h < 24; $h++) {
        $hora = str_pad($h, 2, "0", STR_PAD_LEFT) . ":00";
        $result['hourly']['horas'][] = $hora;
        $result['hourly']['ingresos'][] = 0;
    }
}

header('Content-Type: application/json');
echo json_encode($result);
?>
