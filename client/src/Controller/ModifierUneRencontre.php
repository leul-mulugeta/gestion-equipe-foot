<?php

class ModifierUneRencontre
{
	private readonly Api $api;
	private readonly Rencontre $rencontre;

	public function __construct(Api $api, Rencontre $rencontre)
	{
		$this->api = $api;
		$this->rencontre = $rencontre;
	}

	public function executer(): void
	{
		$response = $this->api->put("/rencontres/{$this->rencontre->getRencontreId()}", Mapper::rencontreToArray($this->rencontre));

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 200) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}
	}
}
