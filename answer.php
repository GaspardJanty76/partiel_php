<?php
require_once 'methodes/dbConnect.php';

$pdoManager = new DBManager('partiel_php');
$pdo = $pdoManager->getPDO();

$message = "";
$afficherFormulaire = true;
$pourcentageReussite = 0;

if (isset($_GET['question_id'])) {
    $questionId = $_GET['question_id'];

    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ? AND suppression = 0");
    $stmt->execute([$questionId]);
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($question) {
        $intitule = htmlspecialchars($question['intitule']);
        $reponseAttendue = $question['reponse'];
        $messageBonneReponse = htmlspecialchars($question['bonne_reponse']);
        $messageMauvaiseReponse = htmlspecialchars($question['mauvaise_reponse']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Envoyer une tentative totale à chaque réponse
            $stmtUpdateTotal = $pdo->prepare("UPDATE questions SET tentatives_totales = tentatives_totales + 1 WHERE id = ?");
            $stmtUpdateTotal->execute([$questionId]);

            $reponseUtilisateur = $_POST['reponse_utilisateur'];

            // Si la réponse est correcte, envoyer une tentative réussie
            if ($reponseUtilisateur === $reponseAttendue) {
                $stmtUpdateReussie = $pdo->prepare("UPDATE questions SET tentatives_reussies = tentatives_reussies + 1 WHERE id = ?");
                $stmtUpdateReussie->execute([$questionId]);
                $message = $messageBonneReponse;
                $afficherFormulaire = false;
            } else {
                $message = $messageMauvaiseReponse;
            }

            // Récupérer les valeurs mises à jour depuis la base de données
            $stmtSelectValues = $pdo->prepare("SELECT tentatives_reussies, tentatives_totales FROM questions WHERE id = ?");
            $stmtSelectValues->execute([$questionId]);
            $values = $stmtSelectValues->fetch(PDO::FETCH_ASSOC);

            // Calculer le pourcentage
            $pourcentageReussite = ($values['tentatives_reussies'] / max($values['tentatives_totales'], 1)) * 100;

            // Mettre à jour le pourcentage en base de données
            $stmtUpdatePourcentage = $pdo->prepare("UPDATE questions SET pourcentage_reussite = ? WHERE id = ?");
            $stmtUpdatePourcentage->execute([$pourcentageReussite, $questionId]);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
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

        <?php if (!$afficherFormulaire && isset($pourcentageReussite)): ?>
            <div class="mt-3">
                <p>Pourcentage de réussite : <?= round($pourcentageReussite, 2) ?>%</p>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
