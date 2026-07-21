<?php
// Gère les réponses de l'API au format JSON

class Api
{
	public function deliverResponse(string $status, int $statusCode, string $message, ?array $data = null): void
	{
		http_response_code($statusCode);
		header('Content-Type: application/json; charset=utf-8');

		$response = [
			'status' => $status,
			'status_code' => $statusCode,
			'status_message' => $message,
			'data' => $data
		];

		$jsonResponse = json_encode($response);
		if ($jsonResponse === false) {
			die('Erreur encodage JSON : ' . json_last_error_msg());
		}

		echo $jsonResponse;
	}
}
