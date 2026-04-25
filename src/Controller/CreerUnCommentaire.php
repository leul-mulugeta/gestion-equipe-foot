<?php

class CreerUnCommentaire
{
	private CommentaireDAO $commentaireDAO;
	private int $id;
	private Joueur $joueur;
	private string $contenu;

	public function __construct(int $id, Joueur $joueur, string $contenu)
	{
		$this->commentaireDAO = new CommentaireDAO();
		$this->id = $id;
		$this->joueur = $joueur;
		$this->contenu = $contenu;
	}

	public function executer(): ?Commentaire
	{
		$commentaire = new Commentaire($this->id, $this->joueur, $this->contenu);
		return $this->commentaireDAO->insert($commentaire);
	}
}
