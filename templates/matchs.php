<?php

$controleurRencontre = new ObtenirToutesLesRencontres();
$rencontres = $controleurRencontre->executer();

$succes = isset($_SESSION['succes']) ? $_SESSION['succes'] : '';
unset($_SESSION['succes']);

?>

<h1>Liste des matchs</h1>

<?php if ($succes) { ?>
	<p class="succes">
		<?= $succes ?>
	</p>
<?php } ?>

<div class="actions">
	<a href="index.php?page=ajouterRencontre"><button>Ajouter un match</button></a>
</div>

<?php if (count($rencontres) > 0) { ?>
	<table>
		<thead>
			<tr>
				<th>Date</th>
				<th>Heure</th>
				<th>Adversaire</th>
				<th>Lieu</th>
				<th>Score</th>
				<th>Résultat</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rencontres as $r) { ?>
				<tr>
					<td><?= $r->getDateEtHeure()->format('d/m/Y') ?></td>
					<td><?= $r->getDateEtHeure()->format('H:i') ?></td>
					<td><?= htmlspecialchars($r->getNomEquipeAdverse()) ?></td>
					<td><?= htmlspecialchars($r->getLieu()->value) ?></td>
					<td>
						<?php if ($r->getScoreEquipeLocale() !== null && $r->getScoreEquipeAdverse() !== null) { ?>
							<?= $r->getScoreEquipeLocale() ?> - <?= $r->getScoreEquipeAdverse() ?>
						<?php } else { ?>
							-
						<?php } ?>
					</td>

					<td><?= $r->getResultat() ? htmlspecialchars($r->getResultat()->value) : 'À venir' ?></td>
					<td><a href="index.php?page=detailRencontre&id=<?= $r->getId() ?>">Détails</a></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } else { ?>
	<p>Aucun match trouvé.</p>
<?php } ?>