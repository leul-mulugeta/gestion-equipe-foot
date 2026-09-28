<?php

class ObtenirTousLesParticipantsDUneRencontre
{
	private readonly Api $api;
	private readonly int $rencontreId;

	public function __construct(Api $api, int $rencontreId)
	{
		$this->api = $api;
		$this->rencontreId = $rencontreId;
	}

	public function executer(): array
	{
		$response = $this->api->get("/rencontres/$this->rencontreId/participants");

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 200) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}

		if (!isset($response['data'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		return array_map(fn($participantData) => Mapper::arrayToParticipant($participantData), $response['data']);
	}
}
