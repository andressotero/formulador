<?php
require '../vendor/autoload.php'; 
include '../dao/functions_dao.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];

        $formulaData = getFormulaData($connection, $id);
        
        if (isset($formulaData['error'])) {
            echo "Error: " . $formulaData['error'];
        } else {
            $formula = $formulaData['formula'];
            $products = $formulaData['products'];
    
            // Crear el archivo Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Agregar datos del cliente
            $sheet->setCellValue('A1', 'Datos del Cliente');
            $sheet->setCellValue('A2', 'Nombre')->setCellValue('B2', $formula['Name']);
            $sheet->setCellValue('A3', 'Empresa')->setCellValue('B3', $formula['Company']);
            $sheet->setCellValue('A4', 'Correo')->setCellValue('B4', $formula['Email']);
            $sheet->setCellValue('A5', 'Teléfono')->setCellValue('B5', $formula['Telephone']);

            // Agregar encabezado para los productos
            $sheet->setCellValue('A7', 'Producto')->setCellValue('B7', 'Dosis Kg/Ha');

            // Agregar datos de los productos
            $row = 8;
            $totalDosage = 0;
            foreach ($products as $product) {
                $sheet->setCellValue("A{$row}", $product['Name']);
                $sheet->setCellValue("B{$row}", $product['CustomValue']);
                $totalDosage += $product['CustomValue'];
                $row++;
            }

            // Agregar el total
            $sheet->setCellValue("A{$row}", 'Total Dosis')->setCellValue("B{$row}", $totalDosage);

            // Configurar encabezados para la descarga
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="detalle_formula.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }
    } catch (PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }
} else {
    die("ID no proporcionado.");
}
