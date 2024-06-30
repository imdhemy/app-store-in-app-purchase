<?php

namespace Imdhemy\AppStore\Jws;

/**
 * Generates ES256 JWT token for App Store Connect API
 */
class AppStoreJwsGenerator implements JwsGenerator
{
    public const AUDIENCE = 'appstoreconnect-v1';

    /**
     * @var GeneratorConfig
     */
    private GeneratorConfig $config;

    /**
     * @param GeneratorConfig $config
     */
    public function __construct(GeneratorConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Generate a JWT
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function generate(array $claims = [], array $headers = []): JsonWebSignature
    {
        $generatorConfig = $this->config;

        $builder = $this->config->config()->builder();

        $token = $builder->withHeader('kid', $generatorConfig->issuer()->key()->kid())
            ->issuedBy($generatorConfig->issuer()->id())
            ->issuedAt($generatorConfig->clock()->now())
            ->expiresAt($generatorConfig->clock()->now()->modify('+1 hour'))
            ->permittedFor(self::AUDIENCE)
            ->withClaim('bid', $generatorConfig->issuer()->bundle())
            ->getToken($generatorConfig->issuer()->signer(), $generatorConfig->issuer()->key());

        return Jws::fromJwtPlain($token);
    }

    /**
     * @return GeneratorConfig
     */
    public function getConfig(): GeneratorConfig
    {
        return $this->config;
    }

    /**
     * @param GeneratorConfig $config
     *
     * @return AppStoreJwsGenerator
     */
    public function setConfig(GeneratorConfig $config): AppStoreJwsGenerator
    {
        $this->config = $config;

        return $this;
    }
}
