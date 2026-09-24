<?php

$erreur = '';
$joueurs = [];

try {
	$controleurJoueur = new ObtenirTousLesJoueurs($apiDonnees);
	$joueurs = $controleurJoueur->executer();
} catch (RuntimeException $e) {
	$erreur = $e->getMessage();
}

$succes = $_SESSION['succes'] ?? '';
unset($_SESSION['succes']);

?>

<h1>Liste des joueurs</h1>

<?php if ($erreur): ?>
	<p class="erreur"><?= htmlspecialchars($erreur) ?></p>
<?php else: ?>
	<?php if ($succes): ?>
		<p class="succes"><?= htmlspecialchars($succes) ?></p>
	<?php endif; ?>
	<div class='actions'>
		<a href='/joueurs/ajouter'><button>Ajouter un joueur</button></a>
	</div>
	<?php if (count($joueurs) > 0): ?>
		<table>
			<thead>
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>N° Licence</th>
					<th>Naissance</th>
					<th>Taille</th>
					<th>Poids</th>
					<th>Poste</th>
					<th>Statut</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($joueurs as $joueur): ?>
					<tr>
						<td><?= htmlspecialchars($joueur->getNom()) ?></td>
						<td><?= htmlspecialchars($joueur->getPrenom()) ?></td>
						<td><?= htmlspecialchars($joueur->getNumeroDeLicence()) ?></td>
						<td><?= $joueur->getDateDeNaissance()->format('d/m/Y') ?></td>
						<td><?= htmlspecialchars($joueur->getTaille()) ?> cm</td>
						<td><?= htmlspecialchars($joueur->getPoids()) ?> kg</td>
						<td><?= htmlspecialchars($joueur->getPoste()->value) ?></td>
						<td><?= htmlspecialchars($joueur->getStatut()->value) ?></td>
						<td><a href="/joueurs/detail?id=<?= $joueur->getJoueurId() ?>">Détails</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Aucun joueur trouvé.</p>
	<?php endif; ?>
<?php endif; ?>