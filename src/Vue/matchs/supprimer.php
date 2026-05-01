<?php

$erreur = '';
$rencontre = null;

// On vérifie si 'id' existe et si c'est bien un nombre
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    $erreur = 'Identifiant de match manquant ou invalide.';
} else {
    $rencontreId = (int) $_GET['id'];
    $obtenirRencontre = new ObtenirUneRencontre($rencontreId);
    $rencontre = $obtenirRencontre->executer();

    if (!$rencontre) {
        $erreur = "Ce match n'existe pas, impossible de le supprimer.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $rencontre) {
    if (isset($_POST['Oui'])) {
        // Vérification de date au moment de la suppression
        if ($rencontre->getDateEtHeure() < new DateTime()) {
            $erreur = 'Impossible de supprimer un match qui a déjà eu lieu.';
        } else {
            $supprimerRencontre = new SupprimerUneRencontre($rencontre->getRencontreId());

            if ($supprimerRencontre->executer()) {
                $_SESSION['succes'] = "Match contre {$rencontre->getNomEquipeAdverse()} du {$rencontre->getDateEtHeure()->format('d/m/Y')} supprimé avec succès.";
                header('Location: /matchs');
                exit;
            } else {
                $erreur = 'Échec de la suppression du match.';
            }
        }
    } else {
        $_SESSION['succes'] = 'Suppression annulée.';
        header('Location: /matchs');
        exit;
    }
}

// Vérification pour l'affichage (Est-ce que le match est passé ?)
$estPasse = false;
if ($rencontre && $rencontre->getDateEtHeure() < new DateTime()) {
    $estPasse = true;
    $erreur = 'Ce match a déjà eu lieu (ou est en cours), il ne peut pas être supprimé.';
}

?>

<?php if ($erreur): ?>
    <p class="erreur"><?= $erreur ?></p>
<?php endif; ?>

<?php if ($rencontre): ?>
    <h1>Supprimer le match contre : <?= htmlspecialchars($rencontre->getNomEquipeAdverse()) ?></h1>

    <div class="fiche">
        <p><strong>Adversaire :</strong> <?= htmlspecialchars($rencontre->getNomEquipeAdverse()) ?></p>
        <p><strong>Date :</strong> <?= $rencontre->getDateEtHeure()->format('d/m/Y à H:i') ?></p>
        <p><strong>Lieu :</strong> <?= htmlspecialchars($rencontre->getLieu()->value) ?></p>
    </div>

    <?php if (!$estPasse): ?>
        <p>Êtes-vous sûr de vouloir supprimer ce match ?</p>
        <form method="post" action="">
            <button type="submit" name="Oui">Oui, supprimer</button>
            <button type="submit" name="Non">Non, annuler</button>
        </form>
    <?php endif; ?>
<?php endif;

if ($erreur || $estPasse): ?>
    <div class="actions">
        <a href="/matchs"><button type="button">Retour à la liste</button></a>
    </div>
<?php endif; ?>