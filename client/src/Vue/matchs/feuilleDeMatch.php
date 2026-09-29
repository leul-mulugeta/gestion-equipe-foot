<?php

// Détermine le type de participation et le poste à afficher dans le formulaire selon la priorité :
// 1. Saisie utilisateur (si retour après erreur POST)
// 2. Données en base (si modification d'une feuille existante)
// 3. Valeurs par défaut du joueur
function determinerTypeDeParticipationEtPoste(?Participant $participantExistant, Joueur $joueur): array
{
    $joueurId = $joueur->getJoueurId();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $typeDeParticipationActuel = $_POST['typeDeParticipation'][$joueurId] ?? '';
        $valeurPoste = $_POST['poste'][$joueurId] ?? '';
        $posteActuel = $valeurPoste !== '' ? $valeurPoste : $joueur->getPoste()->value;
    } else {
        $typeDeParticipationActuel = $participantExistant ? $participantExistant->getTypeDeParticipation()->value : '';
        $posteActuel = $participantExistant ? $participantExistant->getPoste()->value : $joueur->getPoste()->value;
    }
    return [$typeDeParticipationActuel, $posteActuel];
}

$erreur = '';
$rencontre = null;
$estPasse = false;
$participantsExistants = [];
$joueurs = [];
$moyennes = [];

// Initialisation des variables pour pré-remplir le formulaire
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    $erreur = 'Identifiant de match manquant ou invalide.';
} else {
    $rencontreId = (int) $_GET['id'];

    try {
        $obtenirRencontre = new ObtenirUneRencontre($apiDonnees, $rencontreId);
        $rencontre = $obtenirRencontre->executer();

        if ($rencontre->getDateEtHeure() < new DateTime()) {
            $estPasse = true;
            $erreur = 'La feuille de match ne peut plus être modifiée après la rencontre.';
        } else {
            // Récupération de tous les joueurs
            $obtenirJoueurs = new ObtenirTousLesJoueurs($apiDonnees);
            $joueurs = $obtenirJoueurs->executer();

            $obtenirMoyennes = new ObtenirToutesLesMoyennesEvaluationJoueur($apiDonnees);
            $moyennes = $obtenirMoyennes->executer();

            $obtenirParticipants = new ObtenirTousLesParticipantsDUneRencontre($apiDonnees, $rencontreId);
            $participantsExistantsRaw = $obtenirParticipants->executer();

            // On indexe les participants existants par ID de joueur pour accès rapide
            foreach ($participantsExistantsRaw as $participant) {
                $participantsExistants[$participant->getJoueur()->getJoueurId()] = $participant;
            }

            // Tri par poste puis par nom alphabétique
            $ordrePostes = ['GARDIEN' => 1, 'DEFENSEUR' => 2, 'MILIEU' => 3, 'ATTAQUANT' => 4];

            usort($joueurs, function ($joueurA, $joueurB) use ($ordrePostes) {
                $ordreJoueurA = $ordrePostes[$joueurA->getPoste()->value] ?? 99;
                $ordreJoueurB = $ordrePostes[$joueurB->getPoste()->value] ?? 99;

                // Si les postes sont différents, on trie par poste
                if ($ordreJoueurA !== $ordreJoueurB) {
                    return $ordreJoueurA - $ordreJoueurB;
                }

                // Sinon, à poste égal, on trie par nom puis prénom
                return strcmp(
                    $joueurA->getNom() . $joueurA->getPrenom(),
                    $joueurB->getNom() . $joueurB->getPrenom()
                );
            });
        }
    } catch (RuntimeException $e) {
        $erreur = $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $rencontre && !$erreur) {
    $typeDeParticipations = $_POST['typeDeParticipation'] ?? [];
    $postes = $_POST['poste'] ?? [];

    if (!is_array($typeDeParticipations) || !is_array($postes)) {
        $erreur = 'Les données du formulaire sont invalides.';
    } else {
        $participants = [];
        foreach ($typeDeParticipations as $joueurId => $typeDeParticipation) {
            if (empty($typeDeParticipation)) {
                continue;
            }

            $participants[] = [
                'joueurId' => (int) $joueurId,
                'typeDeParticipation' => $typeDeParticipation,
                'poste' => $postes[$joueurId] ?? ''
            ];
        }

        try {
            $sauvegarderParticipants = new SauvegarderParticipantsDUneRencontre($apiDonnees, $rencontreId, $participants);
            $sauvegarderParticipants->executer();
            $_SESSION['succes'] = 'Feuille de match enregistrée avec succès.';
            header('Location: /matchs/detail?id=' . $rencontre->getRencontreId());
            exit;
        } catch (RuntimeException $e) {
            $erreur = $e->getMessage();
        }
    }
}

?>

<?php if ($erreur): ?>
    <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<?php if ($rencontre): ?>
    <h1>Feuille de match contre : <?= htmlspecialchars($rencontre->getNomEquipeAdverse()) ?></h1>
    <div class="fiche">
        <p><strong>Date :</strong> <?= $rencontre->getDateEtHeure()->format('d/m/Y à H:i') ?></p>
        <p><strong>Lieu :</strong> <?= $rencontre->getLieu()->value ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($rencontre->getAdresse()) ?></p>
    </div>
<?php endif; ?>

<?php if ($rencontre && !$estPasse): ?>
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
                <?php foreach ($joueurs as $joueur): ?>
                    <?php
                    $joueurId = $joueur->getJoueurId();
                    $participantExistant = $participantsExistants[$joueurId] ?? null;

                    $estActif = $joueur->getStatut()->value === 'ACTIF';
                    $styleInactif = !$estActif ? 'color: #999; font-style: italic;' : '';
                    [$typeDeParticipationActuel, $posteActuel] = determinerTypeDeParticipationEtPoste($participantExistant, $joueur);

                    $moyenne = $moyennes[$joueurId] ?? 0;
                    ?>
                    <tr style="<?= $styleInactif ?>">
                        <td>
                            <?= htmlspecialchars($joueur->getNomComplet()) ?>
                            <?php if (!$estActif): ?>
                                <small>(<?= $joueur->getStatut()->value ?>)</small>
                            <?php endif; ?>
                        </td>
                        <td><?= $joueur->getTaille() ?>cm / <?= $joueur->getPoids() ?>kg</td>
                        <td><?= $moyenne > 0 ? number_format($moyenne, 1) . ' / 5' : '-' ?></td>
                        <td><?= $joueur->getPoste()->value ?></td>
                        <td>
                            <select name="typeDeParticipation[<?= $joueur->getJoueurId() ?>]" <?= $estActif ? '' : 'disabled' ?>>
                                <option value="">-- Non sélectionné --</option>
                                <?php if ($estActif || $typeDeParticipationActuel): ?>
                                    <option value="TITULAIRE" <?= $typeDeParticipationActuel === 'TITULAIRE' ? 'selected' : '' ?>>
                                        Titulaire</option>
                                    <option value="REMPLACANT" <?= $typeDeParticipationActuel === 'REMPLACANT' ? 'selected' : '' ?>>
                                        Remplaçant</option>
                                <?php endif; ?>
                            </select>
                        </td>
                        <td>
                            <select name="poste[<?= $joueur->getJoueurId() ?>]" <?= $estActif ? '' : 'disabled' ?>>
                                <?php foreach (Poste::cases() as $poste): ?>
                                    <option value="<?= $poste->value ?>" <?= $posteActuel === $poste->value ? 'selected' : '' ?>>
                                        <?= $poste->value ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="actions">
            <button type="submit">Enregistrer</button>
            <a href="/matchs/detail?id=<?= $rencontreId ?>"><button type="button">Annuler</button></a>
        </div>
    </form>
<?php endif; ?>

<?php if ($erreur): ?>
    <div class="actions">
        <a href="/matchs"><button type="button">Retour à la liste</button></a>
    </div>
<?php endif; ?>