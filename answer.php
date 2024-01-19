<?php
// answer.php

require_once 'methodes/dbConnect.php';

$pdoManager = new DBManager('partiel_php');
$pdo = $pdoManager->getPDO();

// Vérifier si question_id est défini dans l'URL
if (isset($_GET['question_id'])) {
    $questionId = $_GET['question_id'];

    // Récupérer les informations de la question depuis la base de données
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ? AND suppression = 0");
    $stmt->execute([$questionId]);
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si la question existe
    if ($question) {
        $intitule = htmlspecialchars($question['intitule']);
        $reponseAttendue = $question['reponse'];
        $messageBonneReponse = htmlspecialchars($question['bonne_reponse']);
        $messageMauvaiseReponse = htmlspecialchars($question['mauvaise_reponse']);

        // Initialiser le message à afficher
        $message = "";

        // Initialiser une variable pour indiquer si le formulaire doit être affiché
        $afficherFormulaire = true;

        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reponseUtilisateur = $_POST['reponse_utilisateur'];

            // Mettre à jour les statistiques de la question
            $stmtUpdate = $pdo->prepare("UPDATE questions SET tentatives_totales = tentatives_totales + 1 WHERE id = ?");
            $stmtUpdate->execute([$questionId]);

            if ($reponseUtilisateur === $reponseAttendue) {
                $message = $messageBonneReponse;
                // Si la réponse est correcte, mettre à jour les statistiques
                $stmtUpdateReussie = $pdo->prepare("UPDATE questions SET tentatives_reussies = tentatives_reussies + 1 WHERE id = ?");
                $stmtUpdateReussie->execute([$questionId]);

                // Calculer le pourcentage de réussite
                $pourcentageReussite = ($question['tentatives_reussies'] / max($question['tentatives_totales'], 1)) * 100;

                // Mettre à jour le pourcentage de réussite dans la base de données
                $stmtUpdatePourcentage = $pdo->prepare("UPDATE questions SET pourcentage_reussite = ? WHERE id = ?");
                $stmtUpdatePourcentage->execute([$pourcentageReussite, $questionId]);

                // Si la réponse est correcte, ne pas afficher le formulaire
                $afficherFormulaire = false;
            } else {
                $message = $messageMauvaiseReponse;
            }
        }
    } else {
        $message = "Question non trouvée.";
    }
} else {
    $message = "Veuillez renseigner un ID de question.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Répondre à la question</title>
    <!-- Ajout des styles Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= isset($intitule) ? $intitule : "Erreur" ?></h1>

        <?php if ($afficherFormulaire): ?>
            <form method="post" action="answer.php?question_id=<?= $questionId ?>">
                <div class="mb-3">
                    <label for="reponse_utilisateur" class="form-label">Votre réponse:</label>
                    <input type="text" class="form-control" name="reponse_utilisateur" required>
                </div>
                <button type="submit" class="btn btn-primary">Vérifier la réponse</button>
            </form>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <p><?= $message ?></p>
        <?php endif; ?>
    </div>

    <!-- Ajout des scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
