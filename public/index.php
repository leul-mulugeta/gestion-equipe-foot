<?php
session_start();

require_once '../init.php';

// Si l'utilisateur est connecté, la page par défaut est 'joueurs', sinon 'login'
$pageDefaut = isset($_SESSION['id']) ? 'accueil' : 'login';
$page = $_GET['page'] ?? $pageDefaut;

// Gestion de la déconnexion
if ($page === 'logout') {
	session_unset();
	session_destroy();
	header("Location: index.php?page=login");
	exit;
}

// Si on essaie d'accéder à une page protégée sans être connecté
if (!isset($_SESSION['id']) && $page !== 'login') {
	$_SESSION['erreur'] = "Vous devez être connecté pour consulter cette page.";
	header("Location: index.php?page=login");
	exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8" />
	<title>Gestion équipe de football</title>
	<link rel="stylesheet" href="css/style.css">
</head>

<body>

	<?php
	if ($page !== 'login') {
	?>
		<nav>
			<ul>
				<li><a href="index.php?page=accueil" class="<?php echo $page === 'accueil' ? 'active' : ''; ?>">Accueil</a></li>
				<li><a href="index.php?page=joueurs" class="<?php echo $page === 'joueurs' ? 'active' : ''; ?>">Joueurs</a></li>
				<li><a href="index.php?page=matchs" class="<?php echo $page === 'matchs' ? 'active' : ''; ?>">Matchs</a></li>
				<li><a href="index.php?page=statistiques" class="<?php echo $page === 'statistiques' ? 'active' : ''; ?>">Statistiques</a></li>
				<li><a href="index.php?page=logout">Déconnexion</a></li>
			</ul>
		</nav>
	<?php
	}
	?>

	<div id="contenu">
		<?php
		if ($page === 'login') {
			include('../templates/login.php');
		} elseif ($page === 'accueil') {
			include('../templates/accueil.php');
		} elseif ($page === 'joueurs') {
			include('../templates/joueurs.php');
		} elseif ($page === 'ajouterJoueur') {
			include('../templates/ajouterJoueur.php');
		} elseif ($page === 'detailJoueur') {
			include('../templates/detailJoueur.php');
		} elseif ($page === 'modifierJoueur') {
			include('../templates/modifierJoueur.php');
		} elseif ($page === 'supprimerJoueur') {
			include('../templates/supprimerJoueur.php');
		} elseif ($page === 'matchs') {
			include('../templates/matchs.php');
		} elseif ($page === 'ajouterRencontre') {
			include('../templates/ajouterRencontre.php');
		} elseif ($page === 'detailRencontre') {
			include('../templates/detailRencontre.php');
		} elseif ($page === 'feuilleDeMatch') {
			include('../templates/feuilleDeMatch.php');
		} elseif ($page === 'modifierRencontre') {
			include('../templates/modifierRencontre.php');
		} elseif ($page === 'supprimerRencontre') {
			include('../templates/supprimerRencontre.php');
		} elseif ($page === 'statistiques') {
			include('../templates/statistiques.php');
		} else {
			echo "<h1>Page non trouvée</h1>";
		}
		?>
	</div>
</body>

</html>