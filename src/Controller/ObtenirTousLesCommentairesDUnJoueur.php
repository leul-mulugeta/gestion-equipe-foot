<?php

class ObtenirTousLesCommentairesDUnJoueur
{
	private readonly CommentaireDAO $commentaireDAO;
	private readonly int $joueurId;

	public function __construct(int $joueurId)
	{
		$this->commentaireDAO = CommentaireDAO::getInstance();
		$this->joueurId = $joueurId;
	}

	public function executer(): array
	{
		return $this->commentaireDAO->selectCommentaireByJoueurId($this->joueurId);
	}
}
