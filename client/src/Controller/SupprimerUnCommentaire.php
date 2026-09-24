<?php

class SupprimerUnCommentaire
{
	private readonly Api $api;
	private readonly int $commentaireId;

	public function __construct(Api $api, int $commentaireId)
	{
		$this->api = $api;
		$this->commentaireId = $commentaireId;
	}

	public function executer(): bool
	{
		$response = $this->api->delete("/commentaires/$this->commentaireId");

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 200) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}

		return true;
	}
}
