<?php
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

function getExcelHeaders($filePath) {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    return $sheet->rangeToArray('A1:Z1')[0];
}

function readExcelData($filePath) {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();
    
    // Obtener encabezados
    $headers = array_shift($data);
    
    // Convertir cada fila en un array asociativo
    $formattedData = [];
    foreach ($data as $row) {
        $formattedRow = array_combine($headers, $row);
        $formattedData[] = $formattedRow;
    }

    return $formattedData;
}

?>  