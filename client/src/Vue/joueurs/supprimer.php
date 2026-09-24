<?php

$erreur = '';
$joueur = null;

// On vérifie si 'id' existe et si c'est bien un nombre
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
	$erreur = 'Identifiant de joueur manquant ou invalide.';
} else {
	$joueurId = (int) $_GET['id'];

	try {
		$obtenirJoueur = new ObtenirUnJoueur($apiDonnees, $joueurId);
		$joueur = $obtenirJoueur->executer();
	} catch (RuntimeException $e) {
		$erreur = $e->getMessage();
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $joueur) {
	if (isset($_POST['Oui'])) {
		try {
			$supprimerJoueur = new SupprimerUnJoueur($apiDonnees, $joueur->getJoueurId());
			$supprimerJoueur->executer();

			$_SESSION['succes'] = "Le joueur {$joueur->getNomComplet()} (N° {$joueur->getNumeroDeLicence()}) a bien été supprimé.";
			header('Location: /joueurs');
			exit;
		} catch (RuntimeException $e) {
			$erreur = $e->getMessage();
		}
	} else {
		$_SESSION['succes'] = 'Suppression annulée.';
		header('Location: /joueurs');
		exit;
	}
}
?>

<?php if ($erreur): ?>
	<p class="erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<?php if ($joueur): ?>
	<h1>Supprimer le joueur : <?= htmlspecialchars($joueur->getNomComplet()) ?></h1>

	<p>
		Êtes-vous sûr de vouloir supprimer le joueur
		<strong><?= htmlspecialchars($joueur->getNomComplet()) ?></strong> ?
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