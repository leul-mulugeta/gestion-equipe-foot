<?php

$erreur = '';
$rencontre = null;

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

?>

<?php if ($rencontre) { ?>
    <h1>Détails du match contre : <?= htmlspecialchars($rencontre->getNomEquipeAdverse()) ?></h1>

    <div class="actions">
        <?php if ($rencontre->getDateEtHeure() > new DateTime()) { ?>
            <a href="/feuilleDeMatch?id=<?= $rencontre->getRencontreId() ?>"><button type="button">Feuille de match</button></a>
        <?php } ?>
        <a href="/matchs/modifier?id=<?= $rencontre->getRencontreId() ?>"><button type="button">Modifier</button></a>
        <a href="/matchs/supprimer?id=<?= $rencontre->getRencontreId() ?>"><button type="button">Supprimer</button></a>
    </div>

    <div class="fiche">
        <p><strong>Date :</strong> <?= $rencontre->getDateEtHeure()->format('d/m/Y') ?></p>
        <p><strong>Heure :</strong> <?= $rencontre->getDateEtHeure()->format('H:i') ?></p>
        <p><strong>Adversaire :</strong> <?= htmlspecialchars($rencontre->getNomEquipeAdverse()) ?></p>
        <p><strong>Lieu :</strong> <?= htmlspecialchars($rencontre->getLieu()->value) ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($rencontre->getAdresse()) ?></p>

        <p><strong>Score :</strong>
            <?php if ($rencontre->getScoreEquipeLocale() !== null && $rencontre->getScoreEquipeAdverse() !== null) { ?>
                <?= $rencontre->getScoreEquipeLocale() ?> - <?= $rencontre->getScoreEquipeAdverse() ?>
            <?php } else { ?>
                Match non joué
            <?php } ?>
        </p>

        <p><strong>Résultat :</strong>
            <?= $rencontre->getResultat() ? htmlspecialchars($rencontre->getResultat()->value) : 'À venir' ?>
        </p>
    </div>

    <hr>

    <h3>Feuille de match</h3>
    <?php if (count($participants) > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Joueur</th>
                    <th>Poste</th>
                    <th>Rôle</th>
                    <?php if ($rencontre->getResultat() !== null) { ?>
                        <th>Évaluation</th>
                    <?php } ?>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($participants as $p) { ?>
                    <tr>
                        <td><?= htmlspecialchars($p->getJoueur()->getPrenom() . ' ' . $p->getJoueur()->getNom()) ?></td>
                        <td><?= htmlspecialchars($p->getPoste()->value) ?></td>
                        <td><?= htmlspecialchars($p->getTypeDeParticipation()->value) ?></td>
                        <?php if ($rencontre->getResultat() !== null) { ?>
                            <td><?= $p->getEvaluation() > 0 ? $p->getEvaluation() . ' / 5' : '-' ?></td>
                        <?php } ?>
                        <td><a href="/joueurs/detail?id=<?= $p->getJoueur()->getJoueurId() ?>">Détails</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else if ($rencontre->getDateEtHeure() < new DateTime()) { ?>
        <p>Aucune feuille de match n'a été saisie avant la rencontre.</p>
    <?php } else { ?>
        <p>Aucune feuille de match n'a été saisie pour cette rencontre. Allez dans "<a href="/feuilleDeMatch?id=<?= $rencontre->getRencontreId() ?>">Feuille de match</a>" pour sélectionner vos joueurs.</p>
    <?php } ?>

<?php } ?>

<?php if ($erreur) { ?>
    <p class="erreur"><?= $erreur ?></p>
<?php } ?>

<div class="actions">
    <a href="/matchs"><button type="button">Retour à la liste</button></a>
</div>