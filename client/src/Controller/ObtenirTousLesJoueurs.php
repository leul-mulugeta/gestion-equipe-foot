<?php

class ObtenirTousLesJoueurs
{
	private readonly Api $api;

	public function __construct(Api $api)
	{
		$this->api = $api;
	}

	public function executer(): array
	{
		$response = $this->api->get('/joueurs');

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 200) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}

		if (!isset($response['data'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		return array_map(fn($joueurData) => Mapper::arrayToJoueur($joueurData), $response['data']);
	}
}
