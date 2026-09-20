<?php

require_once __DIR__ . '/../init.php';

if (preg_match('/\.(?:png|jpg|jpeg|gif|ico|css|js)\??.*$/', $_SERVER['REQUEST_URI'])) {
	return false;
}

session_start();

$uri = strtok($_SERVER['REQUEST_URI'], '?');
if ($uri !== '/') {
	$uri = rtrim($uri, '/');
}

if ($uri === '/logout') {
	setcookie('token', '', ['expires' => time() - 3600, 'path' => '/']);
	session_unset();
	session_destroy();
	header('Location: /login');
	exit;
}

// Si on essaie d'accéder à une page protégée sans être connecté
if (!isset($_COOKIE['token']) && $uri !== '/login') {
	if ($uri !== '/') {
		$_SESSION['erreur'] = 'Vous devez être connecté pour consulter cette page.';
		$_SESSION['returnUrl'] = $_SERVER['REQUEST_URI'];
	}
	header('Location: /login');
	exit;
}

if ($uri === '/') {
	header('Location: /joueurs');
	exit;
}

$apiAuth = new Api(AUTH_URL);
$apiDonnees = new Api(SERVEUR_URL, $_COOKIE['token'] ?? null);

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
				error_log("Unexpected Error: " . $e->getMessage());
				echo '<p class="erreur">Oups, notre serveur a rencontré un problème technique</p>';
			}
		} else {
			echo '<h1>Page non trouvée</h1>';
		}
		?>
	</div>
</body>

</html>