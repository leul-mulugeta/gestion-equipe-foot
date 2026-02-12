<?php

$erreur = '';
$succes = '';
$joueur = null;
$commentaires = [];

// On vérifie si 'id' existe et si c'est bien un nombre
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
	$erreur = "Identifiant de joueur manquant ou invalide.";
} else {
	$id = (int) $_GET['id'];
	$controleurJoueur = new ObtenirUnJoueur($id);
	$joueur = $controleurJoueur->executer();

	if (!$joueur) {
		$erreur = "Aucun joueur trouvé avec cet identifiant.";
	} else {
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['note'])) {
			$noteTexte = trim($_POST['note']);
			if (!empty($noteTexte)) {
				$creerCom = new CreerUnCommentaire(0, $joueur, $noteTexte);
				$resultatCom = $creerCom->executer();
				if ($resultatCom) {
					$succes = "Commentaire ajouté avec succès.";
				} else {
					$erreur = "Échec de l'ajout du commentaire.";
				}
			} else {
				$erreur = "Le commentaire ne peut pas être vide.";
			}
		}

		$obtenirComs = new ObtenirToutLesCommentairesDUnJoueur($id);
		$commentaires = $obtenirComs->executer();
	}
}

?>

<?php if ($joueur) { ?>
	<h1>Détails du joueur : <?= htmlspecialchars($joueur->getPrenom() . ' ' . $joueur->getNom()) ?></h1>

	<div class="actions">
		<a href="index.php?page=modifierJoueur&id=<?= $joueur->getId() ?>"><button type="button">Modifier</button></a>
		<a href="index.php?page=supprimerJoueur&id=<?= $joueur->getId() ?>"><button type="button">Supprimer</button></a>
	</div>

	<div class="fiche">
		<p><strong>Numéro de licence :</strong> <?= htmlspecialchars($joueur->getNumeroDeLicence()) ?></p>
		<p><strong>Nom :</strong> <?= htmlspecialchars($joueur->getNom()) ?></p>
		<p><strong>Prénom :</strong> <?= htmlspecialchars($joueur->getPrenom()) ?></p>
		<p><strong>Date de naissance :</strong> <?= $joueur->getDateDeNaissance()->format('d/m/Y') ?></p>
		<p><strong>Taille :</strong> <?= htmlspecialchars($joueur->getTaille()) ?> cm</p>
		<p><strong>Poids :</strong> <?= htmlspecialchars($joueur->getPoids()) ?> Kg</p>
		<p><strong>Statut :</strong> <?= htmlspecialchars($joueur->getStatut()->value) ?></p>
		<p><strong>Poste :</strong> <?= htmlspecialchars($joueur->getPoste()->value) ?></p>
	</div>

	<hr>

	<h3>Commentaires</h3>

	<?php if ($succes) { ?>
		<p class="succes"><?= $succes ?></p>
	<?php } ?>

	<?php if (count($commentaires) > 0) { ?>
		<ul class="liste-commentaires">
			<?php foreach ($commentaires as $com) { ?>
				<li>
					<?= htmlspecialchars($com->getNote()) ?>
					<hr>
				</li>
			<?php } ?>
		</ul>
	<?php } else { ?>
		<p>Aucun commentaire pour ce joueur.</p>
	<?php } ?>

	<h4>Ajouter une note :</h4>
	<form method="post" action="">
		<textarea name="note" rows="4" cols="50" placeholder="Saisir votre observation ici..." required></textarea>
		<button type="submit">Ajouter la note</button>
	</form>

<?php } ?>

<?php if ($erreur) { ?>
	<p class="erreur"><?= $erreur ?></p>
<?php } ?>

<div class="actions">
	<a href="index.php?page=joueurs"><button type="button">Retour à la liste</button></a>
</div>
