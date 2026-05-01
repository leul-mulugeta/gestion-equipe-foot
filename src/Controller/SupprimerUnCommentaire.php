<?php

class SupprimerUnCommentaire
{
	private readonly CommentaireDAO $commentaireDAO;
	private readonly int $commentaireId;

	public function __construct(int $commentaireId)
	{
		$this->commentaireDAO = CommentaireDAO::getInstance();
		$this->commentaireId = $commentaireId;
	}

	public function executer(): bool
	{
		try {
			return $this->commentaireDAO->deleteCommentaire($this->commentaireId);
		} catch (Exception $e) {
			return false;
		}
	}
}
