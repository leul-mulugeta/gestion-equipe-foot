<?php

$erreur = '';

// Initialisation des variables pour le formulaire
$dateEtHeure = isset($_POST['dateEtHeure']) ? $_POST['dateEtHeure'] : '';
$nomEquipeAdverse = isset($_POST['nomEquipeAdverse']) ? trim($_POST['nomEquipeAdverse']) : '';
$lieu = isset($_POST['lieu']) ? $_POST['lieu'] : 'DOMICILE';
$adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $rencontre = Mapper::arrayToRencontre($_POST);
        $creerRencontre = new CreerUneRencontre($apiDonnees, $rencontre);
        $creerRencontre->executer();

        $_SESSION['succes'] = "Match contre $nomEquipeAdverse du {$rencontre->getDateEtHeure()->format('d/m/Y')} ajouté avec succès.";
        header('Location: /matchs');
        exit;
    } catch (InvalidArgumentException | RuntimeException $e) {
        $erreur = $e->getMessage();
    }
}

?>

<?php if ($erreur): ?>
    <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<h1>Ajouter un match</h1>

<form method="post" action="/matchs/ajouter">
    <label for="dateEtHeure">Date et heure :</label>
    <input type="datetime-local" id="dateEtHeure" name="dateEtHeure" min="<?= date('Y-m-d\TH:i') ?>"
        max="<?= date('Y-m-d\TH:i', strtotime('+5 years')) ?>" value="<?= htmlspecialchars($dateEtHeure) ?>" required>
    <label for="nomEquipeAdverse">Équipe adverse :</label>
    <input type="text" maxlength="50" id="nomEquipeAdverse" name="nomEquipeAdverse"
        value="<?= htmlspecialchars($nomEquipeAdverse) ?>" required>
    <label for="lieu">Lieu :</label>
    <select id="lieu" name="lieu">
        <option value="DOMICILE" <?= $lieu === 'DOMICILE' ? 'selected' : '' ?>>Domicile</option>
        <option value="EXTERIEUR" <?= $lieu === 'EXTERIEUR' ? 'selected' : '' ?>>Extérieur</option>
    </select>
    <label for="adresse">Adresse :</label>
    <input type="text" maxlength="100" id="adresse" name="adresse" value="<?= htmlspecialchars($adresse) ?>" required>
    <button type="submit">Ajouter</button>
    <a href="/matchs"><button type="button">Annuler</button></a>
</form>