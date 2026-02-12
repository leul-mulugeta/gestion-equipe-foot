<?php

$controleurJoueur = new ObtenirTousLesJoueurs();
$joueurs = $controleurJoueur->executer();

$succes = isset($_SESSION['succes']) ? $_SESSION['succes'] : '';
unset($_SESSION['succes']);

?>

<h1>Liste des joueurs</h1>

<?php if ($succes) { ?>
	<p class="succes">
		<?= $succes ?>
	</p>
<?php } ?>

<div class="actions">
	<a href="index.php?page=ajouterJoueur"><button>Ajouter un joueur</button></a>
</div>

<?php if (count($joueurs) > 0) { ?>
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
			<?php foreach ($joueurs as $j) { ?>
				<tr>
					<td><?= htmlspecialchars($j->getNom()) ?></td>
					<td><?= htmlspecialchars($j->getPrenom()) ?></td>
					<td><?= htmlspecialchars($j->getNumeroDeLicence()) ?></td>
					<td><?= $j->getDateDeNaissance()->format('d/m/Y') ?></td>
					<td><?= htmlspecialchars($j->getTaille()) ?> cm</td>
					<td><?= htmlspecialchars($j->getPoids()) ?> kg</td>
					<td><?= htmlspecialchars($j->getPoste()->value) ?></td>
					<td><?= htmlspecialchars($j->getStatut()->value) ?></td>
					<td><a href="index.php?page=detailJoueur&id=<?= $j->getId() ?>">Détails</a></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } else { ?>
	<p>Aucun joueur trouvé.</p>
<?php } ?>