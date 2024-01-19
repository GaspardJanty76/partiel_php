<?php

require_once 'dbConnect.php';

$pdoManager = new DBManager('partiel_php');
$pdo = $pdoManager->getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si une question doit être supprimée, mettez à jour le champ "supprimer" à 1
    if (isset($_POST['delete']) && isset($_POST['question_id'])) {
        $questionIdToDelete = $_POST['question_id'];
        $stmtDelete = $pdo->prepare("UPDATE questions SET suppression = 1 WHERE id = ?");
        $stmtDelete->execute([$questionIdToDelete]);
        header('Location: ../index.php');
    }
}

?>