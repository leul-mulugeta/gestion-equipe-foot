<?php

$erreur = '';
$rencontre = null;
$participants = [];

// On vérifie si 'id' existe et si c'est bien un nombre
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    $erreur = 'Identifiant de match manquant ou invalide.';
} else {
    $rencontreId = (int) $_GET['id'];

    try {
        $obtenirRencontre = new ObtenirUneRencontre($apiDonnees, $rencontreId);
        $rencontre = $obtenirRencontre->executer();

        $maintenant = new DateTime();
        $estPasse = $rencontre->getDateEtHeure() < $maintenant;

        if ($estPasse) {
            $controleurParticipants = new ObtenirTousLesParticipantsDUneRencontre($apiDonnees, $rencontreId);
            $participants = $controleurParticipants->executer();
        }
    } catch (RuntimeException $e) {
        $erreur = $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $rencontre && !$erreur) {
    try {
        if ($estPasse) {
            // Cas match passé : On ne modifie que le résultat et les évaluations
            $scoreLoc = isset($_POST['scoreEquipeLocale']) && $_POST['scoreEquipeLocale'] !== '' ? (int) $_POST['scoreEquipeLocale'] : null;
            $scoreAdv = isset($_POST['scoreEquipeAdverse']) && $_POST['scoreEquipeAdverse'] !== '' ? (int) $_POST['scoreEquipeAdverse'] : null;
            $evaluationsData = $_POST['evaluation'] ?? [];

            $resultat = [
                'scoreEquipeLocale' => $scoreLoc,
                'scoreEquipeAdverse' => $scoreAdv
            ];

            $evaluations = [];
            foreach ($evaluationsData as $participantId => $note) {
                $evaluations[] = [
                    'participantId' => (int) $participantId,
                    'evaluation' => (int) $note,
                ];
            }

            $modifierRencontre = new ModifierLeResultatDUneRencontre($apiDonnees, $rencontreId, $resultat);
            $modifierRencontre->executer();

            if ($evaluations !== []) {
                $modifierEvaluations = new ModifierEvaluationsParticipants($apiDonnees, $rencontreId, $evaluations);
                $modifierEvaluations->executer();
                $_SESSION['succes'] = 'Résultat et évaluations enregistrés avec succès.';
            } else {
                $_SESSION['succes'] = 'Résultat enregistré avec succès.';
            }
        } else {
            // Cas match futur : On modifie les infos de planification
            $rencontreAModifier = Mapper::arrayToRencontre($_POST);
            $rencontreAModifier->setRencontreId($rencontreId);

            $modifierRencontre = new ModifierUneRencontre($apiDonnees, $rencontreAModifier);
            $modifierRencontre->executer();

            $_SESSION['succes'] = 'Match contre ' . $rencontreAModifier->getNomEquipeAdverse() . ' modifié avec succès.';
        }
        header('Location: /matchs');
        exit;
    } catch (InvalidArgumentException | RuntimeException $e) {
        $erreur = $e->getMessage();
    }
}

?>

<?php if ($erreur): ?>
    <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<?php if ($rencontre): ?>
    <h1>Modifier le match contre : <?= htmlspecialchars($rencontre->getNomEquipeAdverse()) ?></h1>

    <form method="post" action="">
        <?php if ($estPasse): ?>
            <!-- Affichage pour match passé -->
            <div class="fiche">
                <p><strong>Match du :</strong> <?= $rencontre->getDateEtHeure()->format('d/m/Y à H:i') ?></p>
                <p><strong>Adversaire :</strong> <?= htmlspecialchars($rencontre->getNomEquipeAdverse()) ?></p>
                <p><strong>Lieu :</strong> <?= $rencontre->getLieu()->value ?></p>
            </div>
            <fieldset>
                <legend>Saisir le résultat</legend>
                <label for="scoreEquipeLocale">Score Équipe Locale :</label>
                <input type="number" id="scoreEquipeLocale" name="scoreEquipeLocale" min="0"
                    value="<?= htmlspecialchars((string) ($_POST['scoreEquipeLocale'] ?? $rencontre->getScoreEquipeLocale() ?? '')) ?>"
                    required>
                <label for="scoreEquipeAdverse">Score Équipe Adverse :</label>
                <input type="number" id="scoreEquipeAdverse" name="scoreEquipeAdverse" min="0"
                    value="<?= htmlspecialchars((string) ($_POST['scoreEquipeAdverse'] ?? $rencontre->getScoreEquipeAdverse() ?? '')) ?>"
                    required>
            </fieldset>
            <fieldset>
                <legend>Évaluations des joueurs</legend>
                <?php if (count($participants) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Joueur</th>
                                <th>Rôle</th>
                                <th>Note (1 à 5)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($participants as $participant): ?>
                                <tr>
                                    <td><?= htmlspecialchars($participant->getJoueur()->getNomComplet()) ?></td>
                                    <td><?= $participant->getTypeDeParticipation()->value ?></td>
                                    <td>
                                        <input type="number" name="evaluation[<?= $participant->getParticipantId() ?>]" min="1" max="5"
                                            value="<?= htmlspecialchars((string) ($_POST['evaluation'][$participant->getParticipantId()] ?? $participant->getEvaluation() ?? '')) ?>"
                                            required>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="erreur">Aucune feuille de match n'a été saisie avant la rencontre. Impossible d'évaluer les joueurs.
                    </p>
                <?php endif; ?>
            </fieldset>
        <?php else: ?>
            <!-- Affichage pour match futur -->
            <label for="dateEtHeure">Date et heure :</label>
            <input type="datetime-local" id="dateEtHeure" name="dateEtHeure" min="<?= date('Y-m-d\TH:i') ?>"
                max="<?= date('Y-m-d\TH:i', strtotime('+5 years')) ?>"
                value="<?= htmlspecialchars($_POST['dateEtHeure'] ?? $rencontre->getDateEtHeure()->format('Y-m-d\TH:i')) ?>"
                required>
            <label for="nomEquipeAdverse">Équipe adverse :</label>
            <input type="text" id="nomEquipeAdverse" name="nomEquipeAdverse"
                value="<?= htmlspecialchars($_POST['nomEquipeAdverse'] ?? $rencontre->getNomEquipeAdverse()) ?>" required>
            <label for="lieu">Lieu :</label>
            <select id="lieu" name="lieu">
                <option value="DOMICILE" <?= ($_POST['lieu'] ?? $rencontre->getLieu()->value) === 'DOMICILE' ? 'selected' : '' ?>>
                    Domicile</option>
                <option value="EXTERIEUR" <?= ($_POST['lieu'] ?? $rencontre->getLieu()->value) === 'EXTERIEUR' ? 'selected' : '' ?>>Extérieur</option>
            </select>
            <label for="adresse">Adresse :</label>
            <input type="text" id="adresse" name="adresse"
                value="<?= htmlspecialchars($_POST['adresse'] ?? $rencontre->getAdresse()) ?>" required>
        <?php endif; ?>

        <button type="submit">Enregistrer</button>
        <a href="/matchs/detail?id=<?= $rencontre->getRencontreId() ?>"><button type="button">Annuler</button></a>
    </form>
<?php else: ?>
    <div class="actions">
        <a href="/matchs"><button type="button">Retour à la liste</button></a>
    </div>
<?php endif; ?>