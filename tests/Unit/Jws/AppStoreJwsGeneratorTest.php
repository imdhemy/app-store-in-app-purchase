<?php

namespace Imdhemy\AppStore\Tests\Unit\Jws;

use DateTimeImmutable;
use Imdhemy\AppStore\Jws\AppStoreJwsGenerator;
use Imdhemy\AppStore\Jws\GeneratorConfig;
use Imdhemy\AppStore\Jws\Issuer;
use Imdhemy\AppStore\Jws\Key;
use Imdhemy\AppStore\Tests\TestCase;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\HasClaimWithValue;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;

class AppStoreJwsGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function app_store_jwt_generator(): AppStoreJwsGenerator
    {
        $config = $this->getConfig();

        $sut = new AppStoreJwsGenerator($config);
        $sut->setConfig($config);

        $this->assertSame($config, $sut->getConfig());

        return $sut;
    }

    /**
     * @test
     * @depends app_store_jwt_generator
     */
    public function generate(AppStoreJwsGenerator $sut): void
    {
        $generatorConfig = $sut->getConfig();

        $jwt = $sut->generate();

        $token = (new Parser(new JoseEncoder()))->parse($jwt);
        $validator = $generatorConfig->config()->validator();

        $constraints = [
            new IssuedBy('issuer_id'),
            new PermittedFor(AppStoreJwsGenerator::AUDIENCE),
            new HasClaimWithValue('bid', 'com.some.thing'),
        ];
        $this->assertTrue($validator->validate($token, ...$constraints));

        $expectedHeaders = [
            'kid' => '1234567890',
            'alg' => 'ES256',
            'typ' => 'JWT',
        ];
        $this->assertEquals($expectedHeaders, $token->headers()->all());

        $iat = $token->claims()->get('iat')->format('Y-m-d H:i:s');
        $this->assertEquals('2022-09-04 21:00:00', $iat);

        $exp = $token->claims()->get('exp')->format('Y-m-d H:i:s');
        $this->assertEquals('2022-09-04 22:00:00', $exp);
    }

    /**
     * @return GeneratorConfig
     */
    private function getConfig(): GeneratorConfig
    {
        $privateKey = InMemory::plainText($this->getEcdsaPrivateKey());
        $key = new Key('1234567890', $privateKey);
        $issuer = new Issuer('issuer_id', 'com.some.thing', $key, new Sha256());
        $clock = new FrozenClock(new DateTimeImmutable('2022-09-04 21:00:00'));

        return GeneratorConfig::forAppStore($issuer, $clock);
    }
}
