<?php

class Commentaire
{
	private int $commentaireId;
	private string $contenu;

	public function __construct(int $commentaireId, string $contenu)
	{
		$this->commentaireId = $commentaireId;
		$this->contenu = $contenu;
	}

	public function getCommentaireId(): int
	{
		return $this->commentaireId;
	}

	public function setCommentaireId($commentaireId): void
	{
		$this->commentaireId = $commentaireId;
	}

	public function getContenu(): string
	{
		return $this->contenu;
	}
}
