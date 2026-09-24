<?php

class ObtenirTousLesCommentairesDUnJoueur
{
	private readonly Api $api;
	private readonly int $joueurId;

	public function __construct(Api $api, int $joueurId)
	{
		$this->api = $api;
		$this->joueurId = $joueurId;
	}

	public function executer(): array
	{
		$response = $this->api->get("/joueurs/$this->joueurId/commentaires");

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 200) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}

		if (!isset($response['data'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		return array_map(fn($commentaireData) => Mapper::arrayToCommentaire($commentaireData), $response['data']);
	}
}
