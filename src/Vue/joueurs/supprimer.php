<?php

$erreur = '';
$joueur = null;

// On vérifie si 'id' existe et si c'est bien un nombre
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
	$erreur = 'Identifiant de joueur manquant ou invalide.';
} else {
	$joueurId = (int) $_GET['id'];
	$obtenirJoueur = new ObtenirUnJoueur($joueurId);
	$joueur = $obtenirJoueur->executer();

	if (!$joueur) {
		$erreur = "Ce joueur n'existe pas, impossible de le supprimer.";
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $joueur) {
	if (isset($_POST['Oui'])) {
		$supprimerJoueur = new SupprimerUnJoueur($joueur->getJoueurId());

		if ($supprimerJoueur->executer()) {
			$_SESSION['succes'] = "Le joueur {$joueur->getFullName()} (N° {$joueur->getNumeroDeLicence()}) a bien été supprimé.";
			header('Location: /joueurs');
			exit;
		} else {
			$erreur = 'Erreur lors de la suppression. Note : un joueur ayant déjà participé à un match ne peut pas être supprimé.';
		}
	} else {
		$_SESSION['succes'] = 'Suppression annulée.';
		header('Location: /joueurs');
		exit;
	}
}
?>

<?php if ($erreur): ?>
	<p class="erreur"><?= $erreur ?></p>
<?php endif; ?>

<?php if ($joueur): ?>
	<h1>Supprimer le joueur : <?= htmlspecialchars($joueur->getFullName()) ?></h1>

	<p>
		Êtes-vous sûr de vouloir supprimer le joueur
		<strong><?= htmlspecialchars($joueur->getPrenom() . " " . $joueur->getNom()) ?></strong> ?
	</p>
	<p><i>(Licence n°<?= htmlspecialchars($joueur->getNumeroDeLicence()) ?>)</i></p>

	<form method="post" action="">
		<button type="submit" name="Oui">Oui, supprimer</button>
		<button type="submit" name="Non">Non, annuler</button>
	</form>
<?php else: ?>
	<div class="actions">
		<a href="/joueurs"><button type="button">Retour à la liste</button></a>
	</div>
<?php endif; ?>