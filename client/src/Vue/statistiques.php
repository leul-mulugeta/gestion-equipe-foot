<?php

$erreur = '';

try {
    $obtenirGlobales = new ObtenirStatistiquesGlobales($apiDonnees);
    $statsGlobales = $obtenirGlobales->executer();

    $obtenirJoueurs = new ObtenirStatistiquesJoueurs($apiDonnees);
    $statsJoueurs = $obtenirJoueurs->executer();
} catch (RuntimeException $e) {
    $erreur = $e->getMessage();
}

?>

<h2>Statistiques Globales</h2>

<?php if ($erreur): ?>
    <p class="erreur">
        <?= htmlspecialchars($erreur) ?>
    </p>
<?php else: ?>
    <div class="stats-globales">
        <div class="stat-card">
            <h3>Victoires</h3>
            <p><?php echo $statsGlobales['victoires']; ?> (<?php echo $statsGlobales['pourcentageVictoires']; ?>%)</p>
        </div>
        <div class="stat-card">
            <h3>Défaites</h3>
            <p><?php echo $statsGlobales['defaites']; ?> (<?php echo $statsGlobales['pourcentageDefaites']; ?>%)</p>
        </div>
        <div class="stat-card">
            <h3>Nuls</h3>
            <p><?php echo $statsGlobales['nuls']; ?> (<?php echo $statsGlobales['pourcentageNuls']; ?>%)</p>
        </div>
        <div class="stat-card">
            <h3>Total Matchs Joués</h3>
            <p><?php echo $statsGlobales['total']; ?></p>
        </div>
    </div>

    <h2>Statistiques des Joueurs</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Joueur</th>
                    <th>Statut</th>
                    <th>Poste Préféré</th>
                    <th>Titularisations</th>
                    <th>Remplacements</th>
                    <th>Total Participations</th>
                    <th>Note Moyenne</th>
                    <th>Rencontres Gagnées</th>
                    <th>% Victoires</th>
                    <th>Meilleure Série de Sélections</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($statsJoueurs as $stat): ?>
                    <tr>
                        <td><?= htmlspecialchars($stat['prenom'] . ' ' . $stat['nom']) ?></td>
                        <td><?= htmlspecialchars($stat['statut']) ?></td>
                        <td><?= htmlspecialchars($stat['postePrefere'] ?? '-') ?></td>
                        <td><?= $stat['titularisations'] ?></td>
                        <td><?= $stat['remplacements'] ?></td>
                        <td><?= $stat['totalParticipations'] ?></td>
                        <td><?= $stat['moyenneEvaluations'] > 0 ? $stat['moyenneEvaluations'] . '/5' : '-' ?></td>
                        <td><?= $stat['rencontresGagnees'] ?></td>
                        <td><?= $stat['pourcentageGagnes'] ?>%</td>
                        <td><?= $stat['meilleureSerieSelections'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
