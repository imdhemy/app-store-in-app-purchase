<?php

namespace Imdhemy\AppStore\Jws;

use Lcobucci\JWT\Signer\Key as JwtKey;

/**
 * Key value object
 * It represents a key for signing JWTs for App Store Connect API
 * - kid: Key ID
 * - contents: Key content
 * - passphrase: Key passphrase
 */
final class Key implements JwtKey
{
    /**
     * @var string
     */
    private string $kid;

    /**
     * @var JwtKey
     */
    private JwtKey $jwtKey;

    /**
     * @param string $kid
     * @param JwtKey $jwtKey
     */
    public function __construct(string $kid, JwtKey $jwtKey)
    {
        $this->kid = $kid;
        $this->jwtKey = $jwtKey;
    }

    /**
     * @return string
     */
    public function kid(): string
    {
        return $this->kid;
    }

    public function contents(): string
    {
        return $this->jwtKey->contents();
    }

    /**
     * @return string
     */
    public function passphrase(): string
    {
        return $this->jwtKey->passphrase();
    }
}
