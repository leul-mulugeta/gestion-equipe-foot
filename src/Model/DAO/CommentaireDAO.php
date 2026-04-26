<?php

class CommentaireDAO
{
	private static ?CommentaireDAO $instance = null;
	private readonly PDO $pdo;

	private function __construct()
	{
		$this->pdo = DBConnection::getInstance()->getConnection();
	}

	public static function getInstance(): CommentaireDAO
	{
		if (self::$instance === null) {
			self::$instance = new CommentaireDAO();
		}
		return self::$instance;
	}

	public function insertCommentaire(Commentaire $commentaire, int $joueurId): void
	{
		$query = 'INSERT INTO commentaire (joueur_id, contenu) VALUES (:joueur_id, :contenu)';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':joueur_id', $joueurId);
		$statement->bindValue(':contenu', $commentaire->getContenu());
		$statement->execute();
	}

	public function selectCommentaireByJoueurId(int $joueurId): array
	{
		$query = 'SELECT * FROM commentaire WHERE joueur_id = :joueur_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':joueur_id', $joueurId);
		$statement->execute();

		return array_map(fn($dbLine) => $this->arrayToCommentaire($dbLine), $statement->fetchAll());
	}

	public function deleteCommentaire(int $commentaireId): bool
	{
		$query = 'DELETE FROM commentaire WHERE commentaire_id = :commentaire_id';
		$statement = $this->pdo->prepare($query);
		$statement->bindValue(':commentaire_id', $commentaireId);
		$statement->execute();

		return $statement->rowCount() > 0;
	}

	private function arrayToCommentaire(array $dbLine): Commentaire
	{
		return new Commentaire(
			$dbLine['commentaire_id'],
			$dbLine['contenu'],
		);
	}
}
