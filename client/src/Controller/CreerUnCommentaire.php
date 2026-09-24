<?php

class CreerUnCommentaire
{
	private readonly Api $api;
	private readonly int $joueurId;
	private readonly Commentaire $commentaire;

	public function __construct(Api $api, int $joueurId, Commentaire $commentaire)
	{
		$this->api = $api;
		$this->joueurId = $joueurId;
		$this->commentaire = $commentaire;
	}

	public function executer(): bool
	{
		$response = $this->api->post("/joueurs/{$this->joueurId}/commentaires", Mapper::commentaireToArray($this->commentaire));

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 201) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}

		return true;
	}
}
