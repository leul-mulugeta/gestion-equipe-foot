<?php

class SauvegarderParticipantsDUneRencontre
{
	private readonly Api $api;
	private readonly int $rencontreId;
	private readonly array $participants;

	public function __construct(Api $api, int $rencontreId, array $participants)
	{
		$this->api = $api;
		$this->rencontreId = $rencontreId;
		$this->participants = $participants;
	}

	public function executer(): void
	{
		$response = $this->api->put("/rencontres/$this->rencontreId/participants", $this->participants);

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 200) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}
	}
}
