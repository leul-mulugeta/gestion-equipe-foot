<?php

class ModifierLeResultatDUneRencontre
{
	private readonly Api $api;
	private readonly int $rencontreId;
	private readonly array $resultat;

	public function __construct(Api $api, int $rencontreId, array $resultat)
	{
		$this->api = $api;
		$this->rencontreId = $rencontreId;
		$this->resultat = $resultat;
	}

	public function executer(): void
	{
		$response = $this->api->patch("/rencontres/$this->rencontreId/resultat", $this->resultat);

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 200) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}
	}
}
