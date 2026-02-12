<?php

class CreerUnCommentaire
{
	private CommentaireDAO $commentaireDAO;
	private int $id;
	private Joueur $joueur;
	private string $note;

	public function __construct(int $id, Joueur $joueur, string $note)
	{
		$this->commentaireDAO = new CommentaireDAO();
		$this->id = $id;
		$this->joueur = $joueur;
		$this->note = $note;
	}

	public function executer(): ?Commentaire
	{
		$commentaire = new Commentaire($this->id, $this->joueur, $this->note);
		return $this->commentaireDAO->insert($commentaire);
	}
}
