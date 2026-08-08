<?php
// Vérification des JWT émis par le service d'authentification

class JWTVerifier
{
    private OpenSSLAsymmetricKey $publicKey;

    public function __construct(string $publicKeyPath)
    {
        $pem = @file_get_contents($publicKeyPath);

        if ($pem === false) {
            throw new RuntimeException("Clé publique introuvable : $publicKeyPath");
        }

        $publicKey = openssl_pkey_get_public($pem);

        if ($publicKey === false) {
            throw new RuntimeException('Clé publique invalide.');
        }

        $this->publicKey = $publicKey;
    }

    public function isJWTValid(string $jwt): bool
    {
        $tokenParts = explode('.', $jwt);

        if (count($tokenParts) !== 3) {
            return false;
        }

        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $tokenParts;

        $signature = $this->base64urlDecode($signatureEncoded);

        if ($signature === false) {
            return false;
        }

        if (openssl_verify("$headerEncoded.$payloadEncoded", $signature, $this->publicKey, OPENSSL_ALGO_SHA256) !== 1) {
            return false;
        }

        $payloadJson = $this->base64urlDecode($payloadEncoded);

        if ($payloadJson === false) {
            return false;
        }

        $payload = json_decode($payloadJson, true);

        return isset($payload['exp']) && $payload['exp'] >= time();
    }

    private function base64urlDecode(string $data): string|false
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4), true);
    }
}
