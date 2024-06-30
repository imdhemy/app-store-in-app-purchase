<?php

namespace Imdhemy\AppStore\Jws;

use Lcobucci\JWT\Signer;

/**
 * Issuer class
 * This class represents the issuer of the JWT token
 * - iss: id
 * - alg: signer
 * - private key: key
 */
final class Issuer
{
    private string $id;

    /**
     * @var Key
     */
    private Key $key;

    /**
     * @var Signer
     */
    private Signer $signer;

    /**
     * @var string
     */
    private string $bundle;

    /**
     * @param string $id
     * @param string $bundle
     * @param Key $key
     * @param Signer $signer
     */
    public function __construct(string $id, string $bundle, Key $key, Signer $signer)
    {
        $this->id = $id;
        $this->key = $key;
        $this->bundle = $bundle;
        $this->signer = $signer;
    }

    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return Key
     */
    public function key(): Key
    {
        return $this->key;
    }

    /**
     * @return Signer
     */
    public function signer(): Signer
    {
        return $this->signer;
    }

    /**
     * @return string
     */
    public function bundle(): string
    {
        return $this->bundle;
    }
}
