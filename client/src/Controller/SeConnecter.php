<?php

class SeConnecter
{
	private readonly Api $api;
	private readonly string $email;
	private readonly string $password;

	public function __construct(Api $api, string $email, string $password)
	{
		$this->api = $api;
		$this->email = $email;
		$this->password = $password;
	}

	public function executer(): string
	{
		$data = ['email' => $this->email, 'password' => $this->password];
		$response = $this->api->post('/auth/login', $data);

		if (!isset($response['status_code'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		if ($response['status_code'] !== 200) {
			throw new RuntimeException($response['status_message'] ?? 'Une erreur est survenue. Veuillez réessayer.');
		}

		if (!isset($response['data'])) {
			throw new RuntimeException('Une erreur est survenue. Veuillez réessayer.');
		}

		return $response['data']['token'];
	}
}
