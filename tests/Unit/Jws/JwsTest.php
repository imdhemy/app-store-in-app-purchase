<?php

namespace Imdhemy\AppStore\Tests\Unit\Jws;

use Imdhemy\AppStore\Jws\Jws;
use Imdhemy\AppStore\Tests\TestCase;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Plain;

class JwsTest extends TestCase
{
    /**
     * @test
     * @return array
     */
    public function from_jwt_plain(): array
    {
        $plain = $this->getPlain();
        $sut = Jws::fromJwtPlain($plain);

        $this->assertSame((string)$sut, $plain->toString());

        return [$sut, $plain];
    }

    /**
     * @test
     * @depends from_jwt_plain
     */
    public function get_headers(array $args): void
    {
        /**
         * @var Jws $sut
         * @var Plain $plain
         */
        [$sut, $plain] = $args;

        $this->assertSame($sut->getHeaders(), $plain->headers()->all());
    }

    /**
     * @test
     * @depends from_jwt_plain
     */
    public function get_claims(array $args): void
    {
        /**
         * @var Jws $sut
         * @var Plain $plain
         */
        [$sut, $plain] = $args;

        $this->assertSame($sut->getClaims(), $plain->claims()->all());
    }

    /**
     * @test
     * @depends from_jwt_plain
     */
    public function get_signature(array $args): void
    {
        /**
         * @var Jws $sut
         * @var Plain $plain
         */
        [$sut, $plain] = $args;

        $this->assertSame($sut->getSignature(), $plain->signature()->toString());
    }

    /**
     * @return Plain
     */
    private function getPlain(): Plain
    {
        $signer = new Sha256();
        $key = InMemory::plainText($this->getEcdsaPrivateKey());

        return Configuration::forSymmetricSigner($signer, $key)->builder()->getToken($signer, $key);
    }
}
