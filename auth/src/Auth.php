<?php
// Gère les actions liées à l'authentification des utilisateurs

class Auth
{
	private PDO $pdo;
	private JWTUtils $jwtUtils;

	public function __construct(PDO $pdo, JWTUtils $jwtUtils)
	{
		$this->pdo = $pdo;
		$this->jwtUtils = $jwtUtils;
	}

	public function login(string $email, string $password): bool
	{
		$query = 'SELECT * FROM user WHERE email = :email';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':email', $email);

		if (!$statement->execute()) {
			return false;
		}

		$user = $statement->fetch();

		if (!$user || !password_verify($password, $user['password'])) {
			return false;
		}

		return true;
	}

	public function verify(string $jwt): bool
	{
		return $this->jwtUtils->isJWTValid($jwt);
	}
}
