<?php

$erreur = '';
$succes = '';
$rencontre = null;
$joueursActifs = [];
$participantsExistants = [];

// Initialisation des variables pour pré-remplir le formulaire
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    $erreur = "Identifiant de match manquant ou invalide.";
} else {
    $idMatch = (int) $_GET['id'];
    $controleurRencontre = new ObtenirUneRencontre($idMatch);
    $rencontre = $controleurRencontre->executer();

    if (!$rencontre) {
        $erreur = "Aucun match trouvé.";
    } elseif ($rencontre->getDateEtHeure() < new DateTime()) {
        $erreur = "Impossible de modifier la feuille de match d'un match passé.";
    } else {
        // Récupération de tous les joueurs
        $controleurJoueurs = new ObtenirTousLesJoueurs();
        $tousLesJoueurs = $controleurJoueurs->executer();

        $controleurParticipants = new ObtenirTousLesParticipantsDUneRencontre($idMatch);
        $participantsExistantsRaw = $controleurParticipants->executer();

        // On indexe les participants existants par ID de joueur pour accès rapide
        foreach ($participantsExistantsRaw as $p) {
            $participantsExistants[$p->getJoueur()->getId()] = $p;
        }

        // Tri alphabétique pour une liste propre
        usort($tousLesJoueurs, fn($a, $b) => strcmp($a->getNom() . $a->getPrenom(), $b->getNom() . $b->getPrenom()));
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $rencontre && empty($erreur)) {
    $roles = $_POST['role'] ?? [];
    $postes = $_POST['poste'] ?? [];

    $nouveauxParticipants = [];
    $nbTitulaires = 0;
    $nbRemplacants = 0;
    $nbGardiensTitulaires = 0;

    // On re-récupère tous les joueurs pour vérifier leur statut lors de la validation
    $controleurJoueurs = new ObtenirTousLesJoueurs();
    $joueursMap = [];
    foreach ($controleurJoueurs->executer() as $j) {
        $joueursMap[$j->getId()] = $j;
    }

    foreach ($roles as $idJoueur => $role) {
        if (!empty($role)) {
            // Validation Option A : Vérifier si le joueur est ACTIF
            $joueurConcerne = $joueursMap[$idJoueur] ?? null;
            if ($joueurConcerne && $joueurConcerne->getStatut()->value !== 'ACTIF') {
                $erreur = "Le joueur " . $joueurConcerne->getPrenom() . " " . $joueurConcerne->getNom() . " est " . $joueurConcerne->getStatut()->value . " et ne peut pas être sélectionné.";
                break; // On arrête tout de suite si un joueur invalide est trouvé
            }

            $posteChoisi = $postes[$idJoueur];

            // Comptage des règles
            if ($role === 'TITULAIRE') {
                $nbTitulaires++;
                if ($posteChoisi === 'GARDIEN') {
                    $nbGardiensTitulaires++;
                }
            } elseif ($role === 'REMPLACANT') {
                $nbRemplacants++;
            }

            // Récupération de l'ancienne évaluation si elle existe
            $evaluation = 0;
            if (isset($participantsExistants[$idJoueur])) {
                $evaluation = $participantsExistants[$idJoueur]->getEvaluation();
            }

            $nouveauxParticipants[] = [
                'idJoueur' => $idJoueur,
                'role' => $role,
                'poste' => $posteChoisi,
                'evaluation' => $evaluation
            ];
        }
    }

    // Validation des règles du football
    if ($nbTitulaires !== 11) {
        $erreur = "Il faut exactement 11 titulaires (actuellement : $nbTitulaires).";
    } elseif ($nbGardiensTitulaires !== 1) {
        $erreur = "Il faut exactement 1 gardien parmi les titulaires (actuellement : $nbGardiensTitulaires).";
    } elseif ($nbRemplacants > 7) {
        $erreur = "Il ne peut y avoir plus de 7 remplaçants (actuellement : $nbRemplacants).";
    } else {
        // Sauvegarde
        $controleurSuppression = new SupprimerTousLesParticipantsDUneRencontre($rencontre->getId());
        $controleurSuppression->executer();

        foreach ($nouveauxParticipants as $np) {
            $controleurJoueur = new ObtenirUnJoueur($np['idJoueur']);
            $joueur = $controleurJoueur->executer();
            // On réinjecte l'évaluation sauvegardée (si 0 => null pour la BDD)
            $evalATransmettre = $np['evaluation'] > 0 ? $np['evaluation'] : null;

            $controleurCreation = new CreerUnParticipant(
                0,
                $joueur,
                $rencontre,
                TypeDeParticipation::from($np['role']),
                Poste::from($np['poste']),
                $evalATransmettre
            );
            $controleurCreation->executer();
        }
        $_SESSION['succes'] = "Feuille de match enregistrée avec succès.";
        header("Location: /matchs/detail?id=" . $rencontre->getId());
        exit;
    }
}
?>

<h1>Feuille de match contre : <?= htmlspecialchars($rencontre ? $rencontre->getNomEquipeAdverse() : '') ?></h1>

<?php if ($rencontre) { ?>
    <div class="fiche">
        <p><strong>Date :</strong> <?= $rencontre->getDateEtHeure()->format('d/m/Y à H:i') ?></p>
        <p><strong>Lieu :</strong> <?= htmlspecialchars($rencontre->getLieu()->value) ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($rencontre->getAdresse()) ?></p>
    </div>
<?php } ?>

<?php if ($erreur) { ?>
    <p class="erreur"><?= $erreur ?></p>
<?php } ?>

<?php if ($rencontre) { ?>
    <form method="post" action="" class="form-large">
        <table>
            <thead>
                <tr>
                    <th>Joueur</th>
                    <th>Taille / Poids</th>
                    <th>Moy. Eval.</th>
                    <th>Poste habituel</th>
                    <th>Rôle pour le match</th>
                    <th>Poste pour le match</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tousLesJoueurs as $j) {
                    $id = $j->getId();
                    $pExistant = $participantsExistants[$id] ?? null;

                    $estActif = $j->getStatut()->value === 'ACTIF';
                    $styleInactif = !$estActif ? 'color: #999; font-style: italic;' : '';

                    // Logique de priorité pour l'affichage : 
                    // Saisie utilisateur (si erreur lors du POST)
                    // Donnée en base (si modification existante)
                    // Valeur par défaut
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $roleActuel = $_POST['role'][$id] ?? '';
                        $valeurPoste = $_POST['poste'][$id] ?? '';

                        if ($valeurPoste !== '') {
                            $posteActuel = $valeurPoste;
                        } elseif ($estActif || $roleActuel) {
                            // Si vide mais actif (ou sélectionné), on met le poste habituel par défaut
                            $posteActuel = $j->getPoste()->value;
                        } else {
                            // Sinon, on garde vide
                            $posteActuel = '';
                        }
                    } else {
                        $roleActuel = $pExistant ? $pExistant->getTypeDeParticipation()->value : '';
                        $posteActuel = $pExistant ? $pExistant->getPoste()->value : $j->getPoste()->value;
                    }

                    $controleurMoyenne = new ObtenirMoyenneEvaluationJoueur($id);
                    $moyenne = $controleurMoyenne->executer();
                ?>
                    <tr style="<?= $styleInactif ?>">
                        <td>
                            <?= htmlspecialchars($j->getPrenom() . ' ' . $j->getNom()) ?>
                            <?php if (!$estActif) { ?>
                                <small>(<?= htmlspecialchars($j->getStatut()->value) ?>)</small>
                            <?php } ?>
                        </td>
                        <td><?= $j->getTaille() ?>cm / <?= $j->getPoids() ?>kg</td>
                        <td><?= $moyenne > 0 ? number_format($moyenne, 1) . ' / 5' : '-' ?></td>
                        <td><?= htmlspecialchars($j->getPoste()->value) ?></td>
                        <td>
                            <select name="role[<?= $j->getId() ?>]">
                                <option value="">-- Non sélectionné --</option>
                                <?php if ($estActif || $roleActuel) { ?>
                                    <option value="TITULAIRE" <?= $roleActuel === 'TITULAIRE' ? 'selected' : '' ?>>Titulaire</option>
                                    <option value="REMPLACANT" <?= $roleActuel === 'REMPLACANT' ? 'selected' : '' ?>>Remplaçant</option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <select name="poste[<?= $j->getId() ?>]">
                                <?php if ($estActif || $roleActuel) { ?>
                                    <?php foreach (Poste::cases() as $poste) { ?>
                                        <option value="<?= $poste->value ?>" <?= $posteActuel === $poste->value ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($poste->value) ?>
                                        </option>
                                    <?php } ?>
                                <?php } else { ?>
                                    <option value="">-- Non sélectionné --</option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="actions">
            <button type="submit">Enregistrer</button>
            <a href="/matchs/detail?id=<?= $rencontre->getId() ?>"><button type="button">Annuler</button></a>
        </div>
    </form>
<?php } ?>