<?php

class Api
{
	private readonly string $baseURL;
	private readonly ?string $token;

	public function __construct(string $baseURL, ?string $token = null)
	{
		$this->baseURL = rtrim($baseURL, '/');
		$this->token = $token;
	}

	private function sendRequest(string $endpoint, array $options): ?array
	{
		$ch = curl_init();

		$headers[] = 'Accept: application/json';

		if (isset($options[CURLOPT_POSTFIELDS])) {
			$options[CURLOPT_POSTFIELDS] = json_encode($options[CURLOPT_POSTFIELDS]);
			$headers[] = 'Content-Type: application/json';
		}

		if ($this->token !== null) {
			$headers[] = "Authorization: Bearer $this->token";
		}

		curl_setopt($ch, CURLOPT_URL, $this->baseURL . $endpoint);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt_array($ch, $options);

		$response = curl_exec($ch);

		if ($error = curl_error($ch)) {
			$errorNo = curl_errno($ch);
			throw new RuntimeException("cURL Erreur [{$errorNo}]: {$error}");
		}

		return json_decode($response, true) ?? null;
	}

	public function get(string $endpoint): ?array
	{
		return $this->sendRequest($endpoint, []);
	}

	public function post(string $endpoint, array $data): ?array
	{
		return $this->sendRequest($endpoint, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => $data]);
	}

	public function put(string $endpoint, array $data): ?array
	{
		return $this->sendRequest($endpoint, [CURLOPT_CUSTOMREQUEST => 'PUT', CURLOPT_POSTFIELDS => $data]);
	}

	public function patch(string $endpoint, array $data): ?array
	{
		return $this->sendRequest($endpoint, [CURLOPT_CUSTOMREQUEST => 'PATCH', CURLOPT_POSTFIELDS => $data]);
	}

	public function delete(string $endpoint): ?array
	{
		return $this->sendRequest($endpoint, [CURLOPT_CUSTOMREQUEST => 'DELETE']);
	}
}
