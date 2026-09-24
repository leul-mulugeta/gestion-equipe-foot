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

	try {
		$obtenirJoueur = new ObtenirUnJoueur($apiDonnees, $joueurId);
		$joueur = $obtenirJoueur->executer();
	} catch (RuntimeException $e) {
		$erreur = $e->getMessage();
	}
}

if ($joueur) {
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenu'])) {
		$contenu = trim($_POST['contenu']);

		try {
			$commentaire = new Commentaire(0, $contenu);
			$creerCommentaire = new CreerUnCommentaire($apiDonnees, $joueur->getJoueurId(), $commentaire);
			$creerCommentaire->executer();
			$succes = 'Commentaire ajouté avec succès.';
		} catch (InvalidArgumentException | RuntimeException $e) {
			$erreur = $e->getMessage();
		}
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_commentaire'])) {
		if (!empty($_POST['commentaire_id']) && ctype_digit($_POST['commentaire_id'])) {
			$commentaireId = (int) $_POST['commentaire_id'];

			try {
				$supprimerCommentaire = new SupprimerUnCommentaire($apiDonnees, $commentaireId);
				$supprimerCommentaire->executer();
				$succes = 'Commentaire supprimé avec succès.';
			} catch (RuntimeException $e) {
				$erreur = $e->getMessage();
			}
		} else {
			$erreur = 'Identifiant de commentaire manquant ou invalide.';
		}
	}

	try {
		$obtenirCommentaires = new ObtenirTousLesCommentairesDUnJoueur($apiDonnees, $joueurId);
		$commentaires = $obtenirCommentaires->executer();
	} catch (RuntimeException $e) {
		$erreur = $e->getMessage();
	}
}

?>

<?php if ($erreur): ?>
	<p class="erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<?php if ($joueur): ?>
	<h1>Détails du joueur : <?= htmlspecialchars($joueur->getNomComplet()) ?></h1>

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
					<span><?= htmlspecialchars($commentaire->getContenu()) ?></span>
					<form method="post" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">
						<input type="hidden" name="commentaire_id" value="<?= $commentaire->getCommentaireId() ?>">
						<button type="submit" name="supprimer_commentaire">Supprimer</button>
					</form>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p>Aucun commentaire pour ce joueur.</p>
	<?php endif; ?>

	<h4>Ajouter une note :</h4>
	<form method="post" action="">
		<textarea name="contenu" rows="4" cols="50" placeholder="Saisir votre observation ici..." maxlength="200"
			required></textarea>
		<button type="submit">Ajouter la note</button>
	</form>
<?php endif; ?>

<div class="actions">
	<a href="/joueurs"><button type="button">Retour à la liste</button></a>
</div>