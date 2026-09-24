<?php

class SupprimerUnJoueur
{
	private readonly Api $api;
	private readonly int $joueurId;

	public function __construct(Api $api, int $joueurId)
	{
		$this->api = $api;
		$this->joueurId = $joueurId;
	}

	public function executer(): void
	{
		$response = $this->api->delete("/joueurs/$this->joueurId");

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 200) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}
	}
}
