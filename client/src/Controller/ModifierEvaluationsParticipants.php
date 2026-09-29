<?php

class ModifierEvaluationsParticipants
{
	private readonly Api $api;
	private readonly int $rencontreId;
	private readonly array $evaluations;

	public function __construct(Api $api, int $rencontreId, array $evaluations)
	{
		$this->api = $api;
		$this->rencontreId = $rencontreId;
		$this->evaluations = $evaluations;
	}

	public function executer(): void
	{
		$response = $this->api->patch("/rencontres/$this->rencontreId/evaluations", $this->evaluations);

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 200) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}
	}
}
