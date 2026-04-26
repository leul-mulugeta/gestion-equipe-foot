<?php

class CreerUnCommentaire
{
	private CommentaireDAO $commentaireDAO;
	private int $id;
	private int $joueurId;
	private string $contenu;

	public function __construct(int $id, int $joueurId, string $contenu)
	{
		$this->commentaireDAO = new CommentaireDAO();
		$this->id = $id;
		$this->joueurId = $joueurId;
		$this->contenu = $contenu;
	}

	public function executer(): ?Commentaire
	{
		$commentaire = new Commentaire($this->id, $this->contenu);
		return $this->commentaireDAO->insert($commentaire, $this->joueurId);
	}
}
