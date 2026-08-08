<?php
// Signature des JWT avec la clé privée du service d'authentification

class JWTSigner
{
    private OpenSSLAsymmetricKey $privateKey;

    public function __construct(string $privateKeyPath)
    {
        $pem = @file_get_contents($privateKeyPath);

        if ($pem === false) {
            throw new RuntimeException("Clé privée introuvable : $privateKeyPath");
        }

        $privateKey = openssl_pkey_get_private($pem);

        if ($privateKey === false) {
            throw new RuntimeException('Clé privée invalide.');
        }

        $this->privateKey = $privateKey;
    }

    public function generateJWT(array $payload): string
    {
        $headers = ['alg' => 'RS256', 'typ' => 'JWT'];

        $headersEncoded = $this->base64urlEncode(json_encode($headers));
        $payloadEncoded = $this->base64urlEncode(json_encode($payload));

        $signature = '';

        if (!openssl_sign("$headersEncoded.$payloadEncoded", $signature, $this->privateKey, OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('Signature du JWT impossible.');
        }

        return "$headersEncoded.$payloadEncoded." . $this->base64urlEncode($signature);
    }

    private function base64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
