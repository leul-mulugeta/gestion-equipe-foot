<?php

class ObtenirTousLesCommentairesDUnJoueur
{
	private readonly CommentaireDAO $commentaireDAO;
	private readonly JoueurDAO $joueurDAO;
	private readonly int $joueurId;

	public function __construct(int $joueurId)
	{
		$this->commentaireDAO = CommentaireDAO::getInstance();
		$this->joueurDAO = JoueurDAO::getInstance();
		$this->joueurId = $joueurId;
	}

	public function executer(): array
	{
		$this->joueurDAO->selectJoueurById($this->joueurId);

		return $this->commentaireDAO->selectCommentaireByJoueurId($this->joueurId);
	}
}
