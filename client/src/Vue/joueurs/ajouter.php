<?php

$erreur = '';

// Initialisation des variables pour pré-remplir le formulaire
$numeroDeLicence = isset($_POST['numeroDeLicence']) ? trim($_POST['numeroDeLicence']) : '';
$nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
$prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
$dateDeNaissance = isset($_POST['dateDeNaissance']) ? trim($_POST['dateDeNaissance']) : '';
$taille = isset($_POST['taille']) ? trim($_POST['taille']) : '';
$poids = isset($_POST['poids']) ? trim($_POST['poids']) : '';
$statut = isset($_POST['statut']) ? trim($_POST['statut']) : 'ACTIF';
$poste = isset($_POST['poste']) ? trim($_POST['poste']) : 'GARDIEN';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	try {
		$joueur = Mapper::arrayToJoueur($_POST);
		$creerJoueur = new CreerUnJoueur($apiDonnees, $joueur);
		$creerJoueur->executer();
		$_SESSION['succes'] = "Le joueur {$joueur->getNomComplet()} (N° $numeroDeLicence) a été ajouté avec succès.";
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

<h1>Ajouter un joueur</h1>

<form method="post" action="/joueurs/ajouter">
	<label for="numeroDeLicence">Numéro de licence :</label>
	<input type="number" min="1" id="numeroDeLicence" name="numeroDeLicence"
		value="<?= htmlspecialchars($numeroDeLicence) ?>" required>
	<label for="nom">Nom :</label>
	<input type="text" maxlength="50" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>" required>
	<label for="prenom">Prénom :</label>
	<input type="text" maxlength="50" id="prenom" name="prenom" value="<?= htmlspecialchars($prenom) ?>" required>
	<label for="dateDeNaissance">Date de naissance :</label>
	<input type="date" id="dateDeNaissance" name="dateDeNaissance" value="<?= htmlspecialchars($dateDeNaissance) ?>"
		required>
	<label for="taille">Taille (en cm) :</label>
	<input type="number" id="taille" name="taille" min="100" max="250" value="<?= htmlspecialchars($taille) ?>"
		required>
	<label for="poids">Poids (en Kg) :</label>
	<input type="number" id="poids" name="poids" min="20" max="200" step="0.1" value="<?= htmlspecialchars($poids) ?>"
		required>
	<label for="statut">Statut :</label>
	<select id="statut" name="statut">
		<option value="ACTIF" <?= $statut === 'ACTIF' ? 'selected' : '' ?>>Actif</option>
		<option value="BLESSE" <?= $statut === 'BLESSE' ? 'selected' : '' ?>>Blessé</option>
		<option value="SUSPENDU" <?= $statut === 'SUSPENDU' ? 'selected' : '' ?>>Suspendu</option>
		<option value="ABSENT" <?= $statut === 'ABSENT' ? 'selected' : '' ?>>Absent</option>
	</select>
	<label for="poste">Poste :</label>
	<select id="poste" name="poste">
		<option value="GARDIEN" <?= $poste === 'GARDIEN' ? 'selected' : '' ?>>Gardien</option>
		<option value="DEFENSEUR" <?= $poste === 'DEFENSEUR' ? 'selected' : '' ?>>Défenseur</option>
		<option value="MILIEU" <?= $poste === 'MILIEU' ? 'selected' : '' ?>>Milieu</option>
		<option value="ATTAQUANT" <?= $poste === 'ATTAQUANT' ? 'selected' : '' ?>>Attaquant</option>
	</select>
	<button type="submit">Ajouter</button>
	<a href="/joueurs"><button type="button">Annuler</button></a>
</form>