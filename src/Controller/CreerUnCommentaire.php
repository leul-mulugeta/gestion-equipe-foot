<?php

class CreerUnCommentaire
{
	private readonly CommentaireDAO $commentaireDAO;
	private readonly Commentaire $commentaire;
	private readonly int $joueurId;

	public function __construct(Commentaire $commentaire, int $joueurId)
	{
		$this->commentaireDAO = CommentaireDAO::getInstance();
		$this->commentaire = $commentaire;
		$this->joueurId = $joueurId;
	}

	public function executer(): bool
	{
		try {
			$this->commentaireDAO->insertCommentaire($this->commentaire, $this->joueurId);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
