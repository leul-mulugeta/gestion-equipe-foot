<?php

class CreerUnCommentaire
{
	private CommentaireDAO $commentaireDAO;
	private int $id;
	private int $joueurId;
	private string $contenu;

	public function __construct(int $id, int $joueurId, string $contenu)
	{
		$this->commentaireDAO = CommentaireDAO::getInstance();
		$this->id = $id;
		$this->joueurId = $joueurId;
		$this->contenu = $contenu;
	}

	public function executer(): bool
	{
		try {
			$commentaire = new Commentaire($this->id, $this->contenu);
			$this->commentaireDAO->insertCommentaire($commentaire, $this->joueurId);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
