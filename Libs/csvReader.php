<?php
function getCSVHeaders($filePath) {
    $file = fopen($filePath, "r");
    $headers = fgetcsv($file);
    fclose($file);
    return $headers;
}

function readCSVData($filePath) {
    $file = fopen($filePath, "r");
    fgetcsv($file); // Saltar encabezados
    $data = [];
    while ($row = fgetcsv($file)) {
        $data[] = $row;
    }
    fclose($file);
    return $data;
}

?>