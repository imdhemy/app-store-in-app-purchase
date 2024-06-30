<?php

namespace Imdhemy\AppStore\Jws;

use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

/**
 * App Store JWS Verifier
 * Verifies a signed payload is signed by Apple
 */
class AppStoreJwsVerifier implements JwsVerifier
{
    private const APPLE_CERTIFICATE_FINGERPRINTS = [
        // Fingerprint of https://www.apple.com/certificateauthority/AppleWWDRCAG6.cer
        '0be38bfe21fd434d8cc51cbe0e2bc7758ddbf97b',
        // Fingerprint of https://www.apple.com/certificateauthority/AppleRootCA-G3.cer
        'b52cb02fd567e0359fe8fa4d4c41037970fe01b0',

    ];

    private const CHAIN_LENGTH = 3;

    /**
     * Verifies the JWS
     *
     * @param JsonWebSignature $jws
     *
     * @return bool
     */
    public function verify(JsonWebSignature $jws): bool
    {
        $x5c = $jws->getHeaders()['x5c'] ?? [];

        if (count($x5c) !== self::CHAIN_LENGTH) {
            return false;
        }

        $chain = $this->chain($x5c);

        [$leaf, $intermediate, $root] = $chain;
        $fingerPrints = [
            openssl_x509_fingerprint($intermediate),
            openssl_x509_fingerprint($root),
        ];

        if (self::APPLE_CERTIFICATE_FINGERPRINTS !== $fingerPrints) {
            return false;
        }

        if (openssl_x509_verify($leaf, $intermediate) !== 1) {
            return false;
        }

        if (openssl_x509_verify($intermediate, $root) !== 1) {
            return false;
        }

        openssl_x509_export($chain[0], $exportedCertificate);
        (new SignedWith(new Sha256(), InMemory::plainText($exportedCertificate)))->assert($jws);

        return true;
    }

    /**
     * @param array $certificates
     *
     * @return string[]
     */
    private function chain(array $certificates): array
    {
        $chain = [];

        foreach ($certificates as $certificate) {
            $chain[] = $this->bas464DerToCert($certificate);
        }

        return $chain;
    }

    /**
     * @param string $certificate
     *
     * @return resource
     */
    private function bas464DerToCert(string $certificate)
    {
        $contents =
            '-----BEGIN CERTIFICATE-----' . PHP_EOL .
            $certificate . PHP_EOL .
            '-----END CERTIFICATE-----';

        return openssl_x509_read($contents);
    }
}
