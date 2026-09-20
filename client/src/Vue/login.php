<?php

if (isset($_COOKIE['token'])) {
	header('Location: /joueurs');
	exit;
}

$erreur = $_SESSION['erreur'] ?? '';
unset($_SESSION['erreur']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {
		try {
			$controleurConnexion = new SeConnecter($apiAuth, $email, $password);
			$token = $controleurConnexion->executer();

			setcookie('token', $token, ['expires' => time() + 3600, 'path' => '/', 'httponly' => true, 'secure' => true]);

			$returnUrl = $_SESSION['returnUrl'] ?? null;
			if ($returnUrl) {
				unset($_SESSION['returnUrl']);

				header("Location: $returnUrl");
				exit;
			}
			header('Location: /joueurs');
			exit;
		} catch (RuntimeException $e) {
			$erreur = $e->getMessage();
		}
	} else {
		$erreur = 'Veuillez remplir tous les champs.';
	}
}

?>

<div class="page-connexion">
	<div class="boite-connexion">
		<h1>Connexion</h1>

		<form method="post" action="/login">
			<label>Email</label>
			<input type="email" name="email" placeholder="Email" required />
			<label>Mot de passe</label>
			<input type="password" name="password" placeholder="Mot de passe" required />
			<button type="submit">Se connecter</button>
		</form>

		<?php if ($erreur): ?>
			<p class="erreur"><?= $erreur ?></p>
		<?php endif; ?>
	</div>
</div>