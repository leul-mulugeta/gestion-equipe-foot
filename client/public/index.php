<?php

require_once __DIR__ . '/../init.php';

$apiDonnees = new Api(SERVEUR_URL);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8" />
	<title>Gestion équipe de football</title>
</head>

<body>
	<h1>Client Web</h1>
	<p>Microservice client fonctionnel sur le port 8080.</p>

	<h2>Test de communication avec l'API Serveur :</h2>
	<?php
	$joueurs = $apiDonnees->get('/joueurs');
	print_r($joueurs);
	?>
</body>

</html>