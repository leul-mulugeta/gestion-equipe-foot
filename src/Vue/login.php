<?php

if (isset($_SESSION['id'])) {
	header("Location: /joueurs");
	exit;
}

$erreur = isset($_SESSION['erreur']) ? $_SESSION['erreur'] : '';
unset($_SESSION['erreur']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {
		$controleurConnexion = new SeConnecter($email, $password);
		if ($controleurConnexion->executer()) {
			session_regenerate_id(true);
			$_SESSION['id'] = time();
			header("Location: /joueurs");
			exit;
		} else {
			$erreur = 'Email ou mot de passe incorrect.';
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

		<?php if ($erreur) { ?>
			<p class="erreur">
				<?= $erreur ?>
			</p>
		<?php } ?>
	</div>
</div>