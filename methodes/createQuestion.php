<?php
require_once 'dbConnect.php';

$pdoManager = new DBManager('partiel_php');
$pdo = $pdoManager->getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $intitule = $_POST['intitule'];
    $reponse = $_POST['reponse'];
    $mauvaiseReponse = $_POST['mauvaiseReponse'];
    $bonneReponseMessage = $_POST['bonneReponseMessage'];

    $stmt = $pdo->prepare("INSERT INTO questions (intitule, reponse, mauvaise_reponse, bonne_reponse, suppression) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$intitule, $reponse, $mauvaiseReponse, $bonneReponseMessage, 0]);
    

    header('Location: ../index.php');
    exit;
}
?>
