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

	public function executer(): void
	{
		if (!$this->commentaireDAO->commentaireExiste($this->commentaireId)) {
			throw new RuntimeException("Ce commentaire n'existe pas.");
		}

		$this->commentaireDAO->deleteCommentaire($this->commentaireId);
	}
}
