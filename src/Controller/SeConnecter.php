<?php

class SeConnecter
{
	private readonly string $email;
	private readonly string $password;

	public function __construct(string $email, string $password)
	{
		$this->email = $email;
		$this->password = $password;
	}

	public function executer(): bool
	{
		return $this->email === COACH_EMAIL && $this->password === COACH_PASSWORD;
	}
}
