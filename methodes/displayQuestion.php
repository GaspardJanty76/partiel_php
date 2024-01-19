<?php
require_once 'methodes/dbConnect.php';


$pdoManager = new DBManager('partiel_php');
$pdo = $pdoManager->getPDO();

$stmt = $pdo->query("SELECT * FROM questions WHERE suppression = 0");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>