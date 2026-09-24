<?php

class Commentaire
{
	private int $commentaireId;
	private string $contenu;

	public function __construct(int $commentaireId, string $contenu)
	{
		$contenu = trim($contenu);
		if ($contenu === '') {
			throw new InvalidArgumentException('Le contenu du commentaire ne peut pas être vide.');
		}
		if (mb_strlen($contenu) > 200) {
			throw new InvalidArgumentException('Le contenu du commentaire ne peut pas dépasser 200 caractères.');
		}

		$this->commentaireId = $commentaireId;
		$this->contenu = $contenu;
	}

	public function getCommentaireId(): int
	{
		return $this->commentaireId;
	}

	public function getContenu(): string
	{
		return $this->contenu;
	}
}
