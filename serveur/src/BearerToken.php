<?php

class BearerToken
{
    public function getAuthorizationHeader(): ?string
    {
        if (isset($_SERVER['Authorization'])) {
            return trim($_SERVER["Authorization"]);
        }

        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return trim($_SERVER["HTTP_AUTHORIZATION"]);
        }

        if (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                return trim($requestHeaders['Authorization']);
            }
        }

        return null;
    }

    public function getBearerToken(): ?string
    {
        $headers = $this->getAuthorizationHeader();

        if (!empty($headers) && preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
