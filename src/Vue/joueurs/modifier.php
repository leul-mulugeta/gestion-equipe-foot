<?php

$erreur = '';
$joueur = null;

// On vérifie si 'id' existe et si c'est bien un nombre
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
	$erreur = "Identifiant de joueur manquant ou invalide.";
} else {
	$joueurId = (int) $_GET['id'];
	$obtenirJoueur = new ObtenirUnJoueur($joueurId);
	$joueur = $obtenirJoueur->executer();

	if (!$joueur) {
		$erreur = "Aucun joueur trouvé avec cet identifiant.";
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $joueur) {
	$numeroDeLicence = trim($_POST['numeroDeLicence']);
	$nom = trim($_POST['nom']);
	$prenom = trim($_POST['prenom']);
	$dateDeNaissance = trim($_POST['dateDeNaissance']);
	$taille = trim($_POST['taille']);
	$poids = trim($_POST['poids']);
	$statut = trim($_POST['statut']);
	$poste = trim($_POST['poste']);

	if (!empty($numeroDeLicence) && !empty($nom) && !empty($prenom) && !empty($dateDeNaissance) && !empty($taille) && !empty($poids) && !empty($statut) && !empty($poste)) {
		try {
			$joueurAModifier = new Joueur(
				$joueur->getJoueurId(),
				(int) $numeroDeLicence,
				$nom,
				$prenom,
				new DateTime($dateDeNaissance),
				(int) $taille,
				(float) $poids,
				Statut::from($statut),
				Poste::from($poste)
			);
			$modifierJoueur = new ModifierUnJoueur($joueurAModifier);
			
			if ($modifierJoueur->executer()) {
				$_SESSION['succes'] = "Le joueur {$joueur->getFullName()} (N° {$joueur->getNumeroDeLicence()}) a été modifié avec succès.";
				header('Location: /joueurs');
				exit;
			} else {
				$erreur = 'Échec de la modification du joueur.';
			}
		} catch (Exception $e) {
			$erreur = 'Données invalides (date, statut ou poste).';
		}
	} else {
		$erreur = 'Veuillez remplir tous les champs.';
	}
}

?>

<?php if ($erreur): ?>
	<p class="erreur"><?= $erreur ?></p>
<?php endif; ?>

<?php if ($joueur): ?>
	<h1>Modifier le joueur : <?= htmlspecialchars($joueur->getFullName()) ?></h1>

	<form method="post" action="">
		<label for="numeroDeLicence">Numéro de licence :</label>
		<input type="number" min="1" id="numeroDeLicence" name="numeroDeLicence" value="<?= htmlspecialchars($joueur->getNumeroDeLicence()) ?>" required>
		<label for="nom">Nom :</label>
		<input type="text" id="nom" name="nom" value="<?= htmlspecialchars($joueur->getNom()) ?>" required>
		<label for="prenom">Prénom :</label>
		<input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($joueur->getPrenom()) ?>" required>
		<label for="dateDeNaissance">Date de naissance :</label>
		<input type="date" id="dateDeNaissance" name="dateDeNaissance" value="<?= $joueur->getDateDeNaissance()->format('Y-m-d') ?>" required>
		<label for="taille">Taille (en cm) :</label>
		<input type="number" id="taille" name="taille" min="120" max="230" value="<?= htmlspecialchars($joueur->getTaille()) ?>" required>
		<label for="poids">Poids (en Kg) :</label>
		<input type="number" id="poids" name="poids" min="30" max="120" step="0.1" value="<?= htmlspecialchars($joueur->getPoids()) ?>" required>
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