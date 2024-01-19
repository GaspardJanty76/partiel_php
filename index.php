<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une question</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-3">Ajouter une question</h1>
        <form method="post" action="methodes/createQuestion.php">
            <div class="mb-3">
                <label for="intitule" class="form-label">Intitulé de la question:</label>
                <input type="text" class="form-control" name="intitule" required>
            </div>
            <div class="mb-3">
                <label for="reponse" class="form-label">Réponse de la question:</label>
                <input type="text" class="form-control" name="reponse" required>
            </div>
            <div class="mb-3">
                <label for="mauvaiseReponse" class="form-label">Message de mauvaise réponse:</label>
                <input type="text" class="form-control" name="mauvaiseReponse" required>
            </div>
            <div class="mb-3">
                <label for="bonneReponseMessage" class="form-label">Message de bonne réponse:</label>
                <input type="text" class="form-control" name="bonneReponseMessage" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter la question</button>
        </form>

        <h2 class="mt-3">Liste des questions</h2>
        <?php
        require_once 'methodes/displayQuestion.php';

        if (empty($questions)): ?>
            <p>Vous n'avez pas encore créé de questions.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($questions as $question): ?>
                    <li>
                        <a href="answer.php?question_id=<?= $question['id'] ?>" class="question-link">
                            <?= htmlspecialchars($question['intitule']) ?>
                        </a>
                        <p><?= htmlspecialchars($question['pourcentage_reussite']) ?>%</p>
                        <form method="post" action="methodes/deleteQuestion.php" style="display:inline;">
                            <input type="hidden" name="question_id" value="<?= $question['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="delete">Supprimer</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
