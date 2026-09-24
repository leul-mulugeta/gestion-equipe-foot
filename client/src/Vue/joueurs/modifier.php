<?php

$erreur = '';
$joueur = null;

// On vérifie si 'id' existe et si c'est bien un nombre
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
	$erreur = 'Identifiant de joueur manquant ou invalide.';
} else {
	$joueurId = (int) $_GET['id'];

	try {
		$controleur = new ObtenirUnJoueur($apiDonnees, $joueurId);
		$joueur = $controleur->executer();
	} catch (RuntimeException $e) {
		$erreur = $e->getMessage();
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $joueur) {
	try {
		$joueurAModifier = Mapper::arrayToJoueur($_POST);
		$joueurAModifier->setJoueurId($joueurId);
		$modifierJoueur = new ModifierUnJoueur($apiDonnees, $joueurAModifier);
		$modifierJoueur->executer();

		$_SESSION['succes'] = "Le joueur {$joueurAModifier->getNomComplet()} (N° {$joueurAModifier->getNumeroDeLicence()}) a été modifié avec succès.";
		header('Location: /joueurs');
		exit;
	} catch (InvalidArgumentException | RuntimeException $e) {
		$erreur = $e->getMessage();
	}
}

?>

<?php if ($erreur): ?>
	<p class="erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<?php if ($joueur): ?>
	<h1>Modifier le joueur : <?= htmlspecialchars($joueur->getNomComplet()) ?></h1>

	<form method="post" action="">
		<label for="numeroDeLicence">Numéro de licence :</label>
		<input type="number" min="1" id="numeroDeLicence" name="numeroDeLicence"
			value="<?= htmlspecialchars($joueur->getNumeroDeLicence()) ?>" required>
		<label for="nom">Nom :</label>
		<input type="text" maxlength="50" id="nom" name="nom" value="<?= htmlspecialchars($joueur->getNom()) ?>" required>
		<label for="prenom">Prénom :</label>
		<input type="text" maxlength="50" id="prenom" name="prenom" value="<?= htmlspecialchars($joueur->getPrenom()) ?>"
			required>
		<label for="dateDeNaissance">Date de naissance :</label>
		<input type="date" id="dateDeNaissance" name="dateDeNaissance"
			value="<?= $joueur->getDateDeNaissance()->format('Y-m-d') ?>" required>
		<label for="taille">Taille (en cm) :</label>
		<input type="number" id="taille" name="taille" min="100" max="250"
			value="<?= htmlspecialchars($joueur->getTaille()) ?>" required>
		<label for="poids">Poids (en Kg) :</label>
		<input type="number" id="poids" name="poids" min="20" max="200" step="0.1"
			value="<?= htmlspecialchars($joueur->getPoids()) ?>" required>
		<label for="statut">Statut :</label>
		<select id="statut" name="statut">
			<option value="ACTIF" <?= $joueur->getStatut() === Statut::ACTIF ? 'selected' : '' ?>>Actif</option>
			<option value="BLESSE" <?= $joueur->getStatut() === Statut::BLESSE ? 'selected' : '' ?>>Blessé</option>
			<option value="SUSPENDU" <?= $joueur->getStatut() === Statut::SUSPENDU ? 'selected' : '' ?>>Suspendu</option>
			<option value="ABSENT" <?= $joueur->getStatut() === Statut::ABSENT ? 'selected' : '' ?>>Absent</option>
		</select>
		<label for="poste">Poste :</label>
		<select id="poste" name="poste">
			<option value="GARDIEN" <?= $joueur->getPoste() === Poste::GARDIEN ? 'selected' : '' ?>>Gardien</option>
			<option value="DEFENSEUR" <?= $joueur->getPoste() === Poste::DEFENSEUR ? 'selected' : '' ?>>Défenseur</option>
			<option value="MILIEU" <?= $joueur->getPoste() === Poste::MILIEU ? 'selected' : '' ?>>Milieu</option>
			<option value="ATTAQUANT" <?= $joueur->getPoste() === Poste::ATTAQUANT ? 'selected' : '' ?>>Attaquant</option>
		</select>
		<button type="submit">Enregistrer</button>
		<a href="/joueurs/detail?id=<?= $joueur->getJoueurId() ?>"><button type="button">Annuler</button></a>
	</form>

<?php else: ?>
	<div class="actions">
		<a href="/joueurs"><button type="button">Retour à la liste</button></a>
	</div>
<?php endif; ?>