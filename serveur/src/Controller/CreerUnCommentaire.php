<?php

class CreerUnCommentaire
{
	private readonly CommentaireDAO $commentaireDAO;
	private readonly JoueurDAO $joueurDAO;
	private readonly Commentaire $commentaire;
	private readonly int $joueurId;

	public function __construct(Commentaire $commentaire, int $joueurId)
	{
		$this->commentaireDAO = CommentaireDAO::getInstance();
		$this->joueurDAO = JoueurDAO::getInstance();
		$this->commentaire = $commentaire;
		$this->joueurId = $joueurId;
	}

	public function executer(): void
	{
		$this->joueurDAO->selectJoueurById($this->joueurId);

		$this->commentaireDAO->insertCommentaire($this->commentaire, $this->joueurId);
	}
}
