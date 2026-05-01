<?php

$controleurRencontre = new ObtenirToutesLesRencontres();
$rencontres = $controleurRencontre->executer();

$succes = $_SESSION['succes'] ?? '';
unset($_SESSION['succes']);

?>

<h1>Liste des matchs</h1>

<?php if ($succes): ?>
	<p class="succes"><?= htmlspecialchars($succes) ?></p>
<?php endif; ?>
<div class="actions">
	<a href="/matchs/ajouter"><button>Ajouter un match</button></a>
</div>
<?php if (count($rencontres) > 0): ?>
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
			<?php foreach ($rencontres as $rencontre): ?>
				<tr>
					<td><?= $rencontre->getDateEtHeure()->format('d/m/Y') ?></td>
					<td><?= $rencontre->getDateEtHeure()->format('H:i') ?></td>
					<td><?= htmlspecialchars($rencontre->getNomEquipeAdverse()) ?></td>
					<td><?= htmlspecialchars($rencontre->getLieu()->value) ?></td>
					<td>
						<?php if ($rencontre->getScoreEquipeLocale() !== null && $rencontre->getScoreEquipeAdverse() !== null): ?>
							<?= $rencontre->getScoreEquipeLocale() ?> - <?= $rencontre->getScoreEquipeAdverse() ?>
						<?php else: ?>
							-
						<?php endif; ?>
					</td>

					<td><?= $rencontre->getResultat() ? htmlspecialchars($rencontre->getResultat()->value) : 'À venir' ?></td>
					<td><a href="/matchs/detail?id=<?= $rencontre->getRencontreId() ?>">Détails</a></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<p>Aucun match trouvé.</p>
<?php endif; ?>