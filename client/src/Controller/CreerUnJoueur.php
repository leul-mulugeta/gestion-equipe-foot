<?php

class CreerUnJoueur
{
	private readonly Api $api;
	private readonly Joueur $joueur;

	public function __construct(Api $api, Joueur $joueur)
	{
		$this->api = $api;
		$this->joueur = $joueur;
	}

	public function executer(): bool
	{
		$response = $this->api->post('/joueurs', Mapper::joueurToArray($this->joueur));

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 201) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}

		return true;
	}
}
