<?php

$erreur = '';
$joueur = null;

// On vérifie si 'id' existe et si c'est bien un nombre
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
	$erreur = "Identifiant de joueur manquant ou invalide.";
} else {
	$id = (int) $_GET['id'];
	$controleur = new ObtenirUnJoueur($id);
	$joueur = $controleur->executer();

	if (!$joueur) {
		$erreur = "Ce joueur n'existe pas, impossible de le supprimer.";
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $joueur) {
	if (isset($_POST['Oui'])) {
		$controleurSuppr = new SupprimerUnJoueur($joueur->getId());
		$succes = $controleurSuppr->executer();

		if ($succes) {
			$_SESSION['succes'] = 'Le joueur ' . htmlspecialchars($joueur->getNom()) . ' a bien été supprimé.';
			header("Location: index.php?page=joueurs");
			exit;
		} else {
			$erreur = 'Erreur lors de la suppression. Note : un joueur ayant déjà participé à un match ne peut pas être supprimé.';
		}
	} else {
		$_SESSION['succes'] = 'Suppression annulée.';
		header("Location: index.php?page=joueurs");
		exit;
	}
}
?>

<h1>Supprimer le joueur : <?= htmlspecialchars($joueur ? $joueur->getPrenom() . ' ' . $joueur->getNom() : '') ?></h1>

<?php if ($joueur) { ?>
	<p>
		Êtes-vous sûr de vouloir supprimer le joueur
		<strong><?= htmlspecialchars($joueur->getPrenom() . " " . $joueur->getNom()) ?></strong> ?
	</p>
	<p><i>(Licence n°<?= htmlspecialchars($joueur->getNumeroDeLicence()) ?>)</i></p>

	<form method="post" action="">
		<button type="submit" name="Oui">Oui, supprimer</button>
		<button type="submit" name="Non">Non, annuler</button>
	</form>
<?php } ?>

<?php if ($erreur) { ?>
	<p class="erreur">
		<?= $erreur ?>
	</p>
<?php } ?>