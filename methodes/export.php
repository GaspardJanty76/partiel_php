<?php
require 'dbConnect.php';
require '../vendor/autoload.php';
$pdoManager = new DBManager('partiel_php');
$pdo = $pdoManager->getPDO();

$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

// Sélectionnez la feuille active
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Intitulé de la question');
$sheet->setCellValue('B1', 'Réponse attendue');
$sheet->setCellValue('C1', 'Tentatives réussies');
$sheet->setCellValue('D1', 'Tentatives totales');
$sheet->setCellValue('E1', 'Pourcentage de réussite');



$sql = "SELECT * FROM questions WHERE suppression = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$row = 2;

if (count($result) > 0) {
    foreach ($result as $data) {
        $sheet->setCellValue('A' . $row, $data['intitule']);
        $sheet->setCellValue('B' . $row, $data['reponse']);
        $sheet->setCellValue('C' . $row, $data['tentatives_reussies']);
        $sheet->setCellValue('D' . $row, $data['tentatives_totales']);
        $sheet->setCellValue('E' . $row, $data['pourcentage_reussite']);

        $row++;
    }
}

// Nom du fichier Excel
$filename = 'export_questions.xlsx';

// Créez l'objet Writer pour Excel
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

// Définissez le type de réponse pour le navigateur
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
?>
