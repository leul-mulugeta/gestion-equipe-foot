<?php

require_once __DIR__ . '/../init.php';

session_start();

$uri = strtok($_SERVER["REQUEST_URI"], '?');

// Si on essaie d'accéder à une page protégée sans être connecté
if (!isset($_SESSION['id']) && $uri !== '/login') {
	if ($uri !== '/') {
		$_SESSION['erreur'] = "Vous devez être connecté pour consulter cette page.";
	}
	header("Location: /login");
	exit;
}

if ($uri === '/') {
	header("Location: /joueurs");
	exit;
}

if ($uri === '/logout') {
	session_unset();
	session_destroy();
	header("Location: /login");
	exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8" />
	<title>Gestion équipe de football</title>
	<link rel="stylesheet" href="/css/style.css">
</head>

<body>

	<?php
	if ($uri !== '/login') {
	?>
		<nav>
			<ul>
				<li><a href="/accueil" class="<?php echo $uri === '/accueil' ? 'active' : ''; ?>">Accueil</a></li>
				<li><a href="/joueurs" class="<?php echo str_starts_with($uri, '/joueurs') ? 'active' : ''; ?>">Joueurs</a></li>
				<li><a href="/matchs" class="<?php echo str_starts_with($uri, '/matchs') ? 'active' : ''; ?>">Matchs</a></li>
				<li><a href="/statistiques" class="<?php echo $uri === '/statistiques' ? 'active' : ''; ?>">Statistiques</a></li>
				<li><a href="/logout">Déconnexion</a></li>
			</ul>
		</nav>
	<?php
	}
	?>

	<div id="contenu">
		<?php
		$page = '../templates' . $uri . '.php';
		if (file_exists($page)) {
			require_once $page;
		} else {
			echo "<h1>Page non trouvée</h1>";
		}
		?>
	</div>
</body>

</html>