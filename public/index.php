<?php

require_once __DIR__ . '/../init.php';

session_start();

$uri = strtok($_SERVER['REQUEST_URI'], '?');
if ($uri !== '/') {
	$uri = rtrim($uri, '/');
}

// Si on essaie d'accéder à une page protégée sans être connecté
if (!isset($_SESSION['id']) && $uri !== '/login') {
	if ($uri !== '/') {
		$_SESSION['erreur'] = 'Vous devez être connecté pour consulter cette page.';
	}
	header('Location: /login');
	exit;
}

if ($uri === '/') {
	header('Location: /joueurs');
	exit;
}

if ($uri === '/logout') {
	session_unset();
	session_destroy();
	header('Location: /login');
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
	if ($uri !== '/login'):
	?>
		<nav>
			<ul>
				<li><a href="/joueurs" class="<?= str_starts_with($uri, '/joueurs') ? 'active' : '' ?>">Joueurs</a></li>
				<li><a href="/matchs" class="<?= str_starts_with($uri, '/matchs') || $uri === '/feuilleDeMatch' ? 'active' : '' ?>">Matchs</a></li>
				<li><a href="/statistiques" class="<?= $uri === '/statistiques' ? 'active' : '' ?>">Statistiques</a></li>
				<li><a href="/logout">Déconnexion</a></li>
			</ul>
		</nav>
	<?php
	endif;
	?>

	<div id="contenu">
		<?php
		$page = "../src/Vue$uri.php";
		if (file_exists($page)) {
			try {
				require_once $page;
			} catch (Throwable $e) {
				echo '<p class="erreur">Oups, notre serveur a rencontré un problème technique</p>';
			}
		} else {
			echo '<h1>Page non trouvée</h1>';
		}
		?>
	</div>
</body>

</html>