<?php

$erreur = '';
$rencontre = null;
$participants = [];

// On vérifie si 'id' existe et si c'est bien un nombre
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    $erreur = "Identifiant de match manquant ou invalide.";
} else {
    $id = (int) $_GET['id'];
    $controleur = new ObtenirUneRencontre($id);
    $rencontre = $controleur->executer();

    if (!$rencontre) {
        $erreur = "Aucun match trouvé avec cet identifiant.";
    } else {
        $controleurParticipants = new ObtenirTousLesParticipantsDUneRencontre($id);
        $participants = $controleurParticipants->executer();
    }
}

if ($rencontre) {
    $maintenant = new DateTime();
    $estPasse = $rencontre->getDateEtHeure() < $maintenant;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            if ($estPasse) {
                // Cas match passé : On ne modifie que le résultat et les évaluations
                $resultatVal = isset($_POST['resultat']) && $_POST['resultat'] !== '' ? $_POST['resultat'] : null;
                $scoreLoc = isset($_POST['scoreEquipeLocale']) && $_POST['scoreEquipeLocale'] !== '' ? (int)$_POST['scoreEquipeLocale'] : null;
                $scoreAdv = isset($_POST['scoreEquipeAdverse']) && $_POST['scoreEquipeAdverse'] !== '' ? (int)$_POST['scoreEquipeAdverse'] : null;
                $evaluations = $_POST['evaluation'] ?? [];

                // Les 3 champs sont obligatoires pour un match passé
                if ($resultatVal === null || $scoreLoc === null || $scoreAdv === null) {
                    $erreur = "Veuillez renseigner les deux scores et le résultat.";
                } else {
                    $modifierRencontre = new ModifierUneRencontre(
                        $rencontre->getId(),
                        $rencontre->getDateEtHeure(),
                        $rencontre->getLieu(),
                        $rencontre->getAdresse(),
                        $rencontre->getNomEquipeAdverse(),
                        Resultat::from($resultatVal),
                        $scoreLoc,
                        $scoreAdv
                    );
                    if ($modifierRencontre->executer()) {
                        // Mise à jour des évaluations
                        foreach ($evaluations as $idParticipant => $valeur) {
                            $modifEval = new ModifierEvaluationParticipant((int)$idParticipant, (int)$valeur);
                            $modifEval->executer();
                        }

                        $_SESSION['succes'] = "Résultat et évaluations enregistrés avec succès.";
                        header("Location: index.php?page=matchs");
                        exit;
                    } else {
                        $erreur = "Échec de l'enregistrement.";
                    }
                }
            } else {
                // Cas match futur : On modifie les infos de planification
                $dateVal = $_POST['date'];
                $heureVal = $_POST['heure'];
                $lieuVal = $_POST['lieu'];
                $adresseVal = trim($_POST['adresse']);
                $adversaireVal = trim($_POST['nomEquipeAdverse']);

                $nouvelleDate = new DateTime($dateVal . ' ' . $heureVal);

                // Date ne doit pas être dans le passé
                if ($nouvelleDate < $maintenant->modify('-1 minute')) {
                    $erreur = "La nouvelle date du match ne peut pas être dans le passé.";
                } elseif (empty($adresseVal) || empty($adversaireVal)) {
                    $erreur = "Veuillez remplir tous les champs obligatoires.";
                } else {
                    $modifierRencontre = new ModifierUneRencontre(
                        $rencontre->getId(),
                        $nouvelleDate,
                        Lieu::from($lieuVal),
                        $adresseVal,
                        $adversaireVal,
                        null,
                        null,
                        null
                    );
                    if ($modifierRencontre->executer()) {
                        $_SESSION['succes'] = "Match contre " . htmlspecialchars($adversaireVal) . " modifié avec succès.";
                        header("Location: index.php?page=matchs");
                        exit;
                    } else {
                        $erreur = "Échec de la modification du match.";
                    }
                }
            }
        } catch (Exception $e) {
            $erreur = "Erreur : " . $e->getMessage();
        }
    }
}

?>

<h1>Modifier le match contre : <?= htmlspecialchars($rencontre->getNomEquipeAdverse()) ?></h1>

<?php if ($rencontre) { ?>
    <form method="post" action="">

        <?php if ($estPasse) { ?>
            <!-- Affichage pour match passé -->
            <div class="fiche">
                <p><strong>Match du :</strong> <?= $rencontre->getDateEtHeure()->format('d/m/Y à H:i') ?></p>
                <p><strong>Adversaire :</strong> <?= htmlspecialchars($rencontre->getNomEquipeAdverse()) ?></p>
                <p><strong>Lieu :</strong> <?= htmlspecialchars($rencontre->getLieu()->value) ?></p>
            </div>

            <fieldset>
                <legend>Saisir le résultat</legend>
                <label for="scoreEquipeLocale">Score Équipe Locale :</label>
                <input type="number" id="scoreEquipeLocale" name="scoreEquipeLocale" min="0" value="<?= $rencontre->getScoreEquipeLocale() !== null ? $rencontre->getScoreEquipeLocale() : '' ?>" required>

                <label for="scoreEquipeAdverse">Score Équipe Adverse :</label>
                <input type="number" id="scoreEquipeAdverse" name="scoreEquipeAdverse" min="0" value="<?= $rencontre->getScoreEquipeAdverse() !== null ? $rencontre->getScoreEquipeAdverse() : '' ?>" required>

                <label for="resultat">Issue du match :</label>
                <select id="resultat" name="resultat" required>
                    <option value="">-- Choisir le résultat --</option>
                    <option value="VICTOIRE" <?= $rencontre->getResultat() === Resultat::VICTOIRE ? 'selected' : '' ?>>Victoire</option>
                    <option value="DEFAITE" <?= $rencontre->getResultat() === Resultat::DEFAITE ? 'selected' : '' ?>>Défaite</option>
                    <option value="NUL" <?= $rencontre->getResultat() === Resultat::NUL ? 'selected' : '' ?>>Match Nul</option>
                </select>
            </fieldset>

            <fieldset>
                <legend>Évaluations des joueurs</legend>
                <?php if (count($participants) > 0) { ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Joueur</th>
                                <th>Rôle</th>
                                <th>Note (1 à 5)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($participants as $p) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($p->getJoueur()->getPrenom() . ' ' . $p->getJoueur()->getNom()) ?></td>
                                    <td><?= htmlspecialchars($p->getTypeDeParticipation()->value) ?></td>
                                    <td>
                                        <input type="number" name="evaluation[<?= $p->getId() ?>]" min="1" max="5" value="<?= $p->getEvaluation() ?>" required>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p class="erreur">Aucune feuille de match n'a été saisie avant la rencontre. Impossible d'évaluer les joueurs.</p>
                <?php } ?>
            </fieldset>

        <?php } else { ?>
            <!-- Affichage pour match futur -->
            <label for="date">Date :</label>
            <input type="date" id="date" name="date" min="<?= date('Y-m-d') ?>" value="<?= $rencontre->getDateEtHeure()->format('Y-m-d') ?>" required>

            <label for="heure">Heure :</label>
            <input type="time" id="heure" name="heure" value="<?= $rencontre->getDateEtHeure()->format('H:i') ?>" required>

            <label for="nomEquipeAdverse">Équipe adverse :</label>
            <input type="text" id="nomEquipeAdverse" name="nomEquipeAdverse" value="<?= htmlspecialchars($rencontre->getNomEquipeAdverse()) ?>" required>

            <label for="lieu">Lieu :</label>
            <select id="lieu" name="lieu">
                <option value="DOMICILE" <?= $rencontre->getLieu() === Lieu::DOMICILE ? 'selected' : '' ?>>Domicile</option>
                <option value="EXTERIEUR" <?= $rencontre->getLieu() === Lieu::EXTERIEUR ? 'selected' : '' ?>>Extérieur</option>
            </select>

            <label for="adresse">Adresse :</label>
            <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($rencontre->getAdresse()) ?>" required>
        <?php } ?>

        <button type="submit">Enregistrer</button>
        <a href="index.php?page=detailRencontre&id=<?= $rencontre->getId() ?>"><button type="button">Annuler</button></a>
    </form>
<?php } ?>

<?php if ($erreur) { ?>
    <p class="erreur">
        <?= $erreur ?>
    </p>
<?php } ?>