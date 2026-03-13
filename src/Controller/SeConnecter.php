<?php

class SeConnecter
{
	private string $email;
	private string $password;

	public function __construct($email, $password)
	{
		$this->email = $email;
		$this->password = $password;
	}

	public function executer(): bool
	{
		return $this->email === COACH_EMAIL && $this->password === COACH_PASSWORD;
	}
}
