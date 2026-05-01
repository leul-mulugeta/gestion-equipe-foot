<?php

$erreur = '';
$succes = '';
$joueur = null;
$commentaires = [];

// On vérifie si 'id' existe et si c'est bien un nombre
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
	$erreur = 'Identifiant de joueur manquant ou invalide.';
} else {
	$joueurId = (int) $_GET['id'];
	$obtenirJoueur = new ObtenirUnJoueur($joueurId);
	$joueur = $obtenirJoueur->executer();

	if (!$joueur) {
		$erreur = 'Aucun joueur trouvé avec cet identifiant.';
	}
}

if ($joueur) {
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenu'])) {
		$contenu = trim($_POST['contenu']);

		if (!empty($contenu)) {
			$commentaire = new Commentaire(0, $contenu);
			$creerCommentaire = new CreerUnCommentaire($commentaire, $joueur->getJoueurId());

			if ($creerCommentaire->executer()) {
				$succes = 'Commentaire ajouté avec succès.';
			} else {
				$erreur = "Échec de l'ajout du commentaire.";
			}
		} else {
			$erreur = 'Le commentaire ne peut pas être vide.';
		}
	}

	$obtenirCommentaires = new ObtenirTousLesCommentairesDUnJoueur($joueurId);
	$commentaires = $obtenirCommentaires->executer();
}

?>

<?php if ($erreur): ?>
	<p class="erreur"><?= $erreur ?></p>
<?php endif; ?>

<?php if ($joueur): ?>
	<h1>Détails du joueur : <?= htmlspecialchars($joueur->getFullName()) ?></h1>

	<div class="actions">
		<a href="/joueurs/modifier?id=<?= $joueur->getJoueurId() ?>"><button type="button">Modifier</button></a>
		<a href="/joueurs/supprimer?id=<?= $joueur->getJoueurId() ?>"><button type="button">Supprimer</button></a>
	</div>

	<div class="fiche">
		<p><strong>Numéro de licence :</strong> <?= htmlspecialchars($joueur->getNumeroDeLicence()) ?></p>
		<p><strong>Nom :</strong> <?= htmlspecialchars($joueur->getNom()) ?></p>
		<p><strong>Prénom :</strong> <?= htmlspecialchars($joueur->getPrenom()) ?></p>
		<p><strong>Date de naissance :</strong> <?= $joueur->getDateDeNaissance()->format('d/m/Y') ?></p>
		<p><strong>Taille :</strong> <?= htmlspecialchars($joueur->getTaille()) ?> cm</p>
		<p><strong>Poids :</strong> <?= htmlspecialchars($joueur->getPoids()) ?> Kg</p>
		<p><strong>Statut :</strong> <?= $joueur->getStatut()->value ?></p>
		<p><strong>Poste :</strong> <?= $joueur->getPoste()->value ?></p>
	</div>

	<hr>
	<h3>Commentaires</h3>
	<?php if ($succes): ?>
		<p class="succes"><?= htmlspecialchars($succes) ?></p>
	<?php endif; ?>

	<?php if (count($commentaires) > 0): ?>
		<ul class="liste-commentaires">
			<?php foreach ($commentaires as $commentaire): ?>
				<li>
					<?= htmlspecialchars($commentaire->getContenu()) ?>
					<hr>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p>Aucun commentaire pour ce joueur.</p>
	<?php endif; ?>

	<h4>Ajouter une note :</h4>
	<form method="post" action="">
		<textarea name="contenu" rows="4" cols="50" placeholder="Saisir votre observation ici..." required></textarea>
		<button type="submit">Ajouter la note</button>
	</form>
<?php endif; ?>

<div class="actions">
	<a href="/joueurs"><button type="button">Retour à la liste</button></a>
</div>