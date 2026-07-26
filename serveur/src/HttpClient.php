<?php
// Client HTTP pour communiquer avec l'API d'authentification

class HttpClient
{
	private string $url;

	public function __construct(string $url)
	{
		$this->url = $url;
	}

	public function post(array $data): ?array
	{
		$ch = curl_init();

		$headers = ['Accept: application/json', 'Content-Type: application/json'];

		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$response = curl_exec($ch);

		if ($error = curl_error($ch)) {
			$errorNo = curl_errno($ch);
			throw new RuntimeException("cURL Erreur [{$errorNo}]: {$error}");
		}

		return json_decode($response, true) ?? null;
	}
}
