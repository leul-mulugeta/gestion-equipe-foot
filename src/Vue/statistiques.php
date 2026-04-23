<?php
$obtenirGlobales = new ObtenirStatistiquesGlobales();
$statsGlobales = $obtenirGlobales->executer();

$obtenirJoueurs = new ObtenirStatistiquesJoueurs();
$statsJoueurs = $obtenirJoueurs->executer();
?>

<h2>Statistiques Globales</h2>
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
                <th>Note Moyenne</th>
                <th>% Victoires</th>
                <th>Sélections Consécutives</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($statsJoueurs as $stat) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($stat['prenom'] . ' ' . $stat['nom']); ?></td>
                    <td><?php echo htmlspecialchars($stat['statut']); ?></td>
                    <td><?php echo htmlspecialchars($stat['postePrefere'] ?? '-'); ?></td>
                    <td><?php echo $stat['titularisations']; ?></td>
                    <td><?php echo $stat['remplacements']; ?></td>
                    <td>
                        <?php 
                        if ($stat['moyenneEvaluations'] > 0) {
                            echo $stat['moyenneEvaluations'] . '/5'; 
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                    <td><?php echo $stat['pourcentageGagnes']; ?>%</td>
                    <td><?php echo $stat['selectionsConsecutives']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
