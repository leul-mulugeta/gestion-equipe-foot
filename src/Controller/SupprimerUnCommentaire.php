<?php

class SupprimerUnCommentaire
{
	private CommentaireDAO $commentaireDAO;
	private int $id;

	public function __construct(int $id)
	{
		$this->commentaireDAO = CommentaireDAO::getInstance();
		$this->id = $id;
	}

	public function executer(): bool
	{
		try {
			return $this->commentaireDAO->deleteCommentaire($this->id);
		} catch (Exception $e) {
			return false;
		}
	}
}
