<?php

class ModifierUnJoueur
{
	private readonly Api $api;
	private readonly Joueur $joueur;

	public function __construct(Api $api, Joueur $joueur)
	{
		$this->api = $api;
		$this->joueur = $joueur;
	}

	public function executer(): void
	{
		$response = $this->api->put("/joueurs/{$this->joueur->getJoueurId()}", Mapper::joueurToArray($this->joueur));

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 200) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}
	}
}
