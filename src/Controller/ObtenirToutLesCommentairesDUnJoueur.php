<?php

class ObtenirToutLesCommentairesDUnJoueur
{
	private CommentaireDAO $commentaireDAO;
	private int $idJoueur;

	public function __construct(int $idJoueur)
	{
		$this->commentaireDAO = new CommentaireDAO();
		$this->idJoueur = $idJoueur;
	}

	public function executer(): array
	{
		return $this->commentaireDAO->selectByIdJoueur($this->idJoueur);
	}
}
