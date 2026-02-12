<?php

class SupprimerUnCommentaire
{
	private CommentaireDAO $commentaireDAO;
	private int $id;

	public function __construct(int $id)
	{
		$this->commentaireDAO = new CommentaireDAO();
		$this->id = $id;
	}

	public function executer(): bool
	{
		return $this->commentaireDAO->delete($this->id);
	}
}
