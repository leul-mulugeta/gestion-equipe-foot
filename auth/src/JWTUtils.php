<?php
// Boîte à outils pour la manipulation des JWT

class JWTUtils
{
    private string $jwtSecretKey;

    public function __construct(string $jwtSecretKey)
    {
        $this->jwtSecretKey = $jwtSecretKey;
    }

    public function generateJWT(array $payload): string
    {
        $headers = ['alg' => 'HS256', 'typ' => 'JWT'];

        $headersEncoded = $this->base64urlEncode(json_encode($headers));
        $payloadEncoded = $this->base64urlEncode(json_encode($payload));

        $signature = hash_hmac('SHA256', "$headersEncoded.$payloadEncoded", $this->jwtSecretKey, true);
        $signatureEncoded = $this->base64urlEncode($signature);

        return "$headersEncoded.$payloadEncoded.$signatureEncoded";
    }

    public function isJWTValid(string $jwt): bool
    {
        $tokenParts = explode('.', $jwt);

        if (count($tokenParts) !== 3) {
            return false;
        }

        $headerEncoded = $tokenParts[0];
        $payloadEncoded = $tokenParts[1];
        $signatureProvided = $tokenParts[2];

        $payload = json_decode($this->base64urlDecode($payloadEncoded));

        if (!isset($payload->exp) || $payload->exp < time()) {
            return false;
        }

        $signature = hash_hmac('SHA256', "$headerEncoded.$payloadEncoded", $this->jwtSecretKey, true);
        $signatureCheck = $this->base64urlEncode($signature);

        return hash_equals($signatureCheck, $signatureProvided);
    }

    private function base64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64urlDecode(string $data): string|false
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }
}
