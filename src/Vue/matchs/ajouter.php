<?php

$erreur = '';

// Initialisation des variables pour le formulaire
$dateMatch = isset($_POST['date']) ? $_POST['date'] : '';
$heureMatch = isset($_POST['heure']) ? $_POST['heure'] : '';
$nomEquipeAdverse = isset($_POST['nomEquipeAdverse']) ? trim($_POST['nomEquipeAdverse']) : '';
$lieu = isset($_POST['lieu']) ? $_POST['lieu'] : 'DOMICILE';
$adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($dateMatch) && !empty($heureMatch) && !empty($nomEquipeAdverse) && !empty($lieu) && !empty($adresse)) {
        try {
            $dateEtHeure = new DateTime($dateMatch . ' ' . $heureMatch);
            $rencontre = new Rencontre(
                0,
                $dateEtHeure,
                Lieu::from($lieu),
                $adresse,
                $nomEquipeAdverse,
                null, // Pas de résultat à la création
                null, // Pas de score locale à la création
                null  // Pas de score adverse à la création
            );

            $creerRencontre = new CreerUneRencontre($rencontre);

            if ($creerRencontre->executer()) {
                $_SESSION['succes'] = "Match contre $nomEquipeAdverse du {$rencontre->getDateEtHeure()->format('d/m/Y')} ajouté avec succès.";
                header('Location: /matchs');
                exit;
            } else {
                $erreur = "Échec de l'ajout du match.";
            }
        } catch (Exception $e) {
			$erreur = 'Données invalides (date ou lieu).';
		}
    } else {
        $erreur = 'Veuillez remplir tous les champs obligatoires.';
    }
}

?>

<?php if ($erreur): ?>
    <p class="erreur"><?= $erreur ?></p>
<?php endif; ?>

<h1>Ajouter un match</h1>

<form method="post" action="/matchs/ajouter">
    <label for="date">Date :</label>
    <input type="date" id="date" name="date" min="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($dateMatch) ?>" required>
    <label for="heure">Heure :</label>
    <input type="time" id="heure" name="heure" value="<?= htmlspecialchars($heureMatch) ?>" required>
    <label for="nomEquipeAdverse">Équipe adverse :</label>
    <input type="text" id="nomEquipeAdverse" name="nomEquipeAdverse" value="<?= htmlspecialchars($nomEquipeAdverse) ?>" required>
    <label for="lieu">Lieu :</label>
    <select id="lieu" name="lieu">
        <option value="DOMICILE" <?= $lieu === 'DOMICILE' ? 'selected' : '' ?>>Domicile</option>
        <option value="EXTERIEUR" <?= $lieu === 'EXTERIEUR' ? 'selected' : '' ?>>Extérieur</option>
    </select>
    <label for="adresse">Adresse :</label>
    <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($adresse) ?>" required>
    <button type="submit">Ajouter</button>
    <a href="/matchs"><button type="button">Annuler</button></a>
</form>