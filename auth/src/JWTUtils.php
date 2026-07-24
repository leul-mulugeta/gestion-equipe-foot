<?php
// Boîte à outils pour la manipulation des JWT

class JWTUtils
{
    private string $jwtSecretKey;

    public function __construct(string $jwtSecretKey)
    {
        $this->jwtSecretKey = $jwtSecretKey;
    }

    public function generateJWT(array $headers, array $payload): string
    {
        $headersEncoded = $this->base64urlEncode(json_encode($headers));
        $payloadEncoded = $this->base64urlEncode(json_encode($payload));

        $signature = hash_hmac('SHA256', "$headersEncoded.$payloadEncoded", $this->jwtSecretKey, true);
        $signatureEncoded = $this->base64urlEncode($signature);

        return "$headersEncoded.$payloadEncoded.$signatureEncoded";
    }

    private function base64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
