<?php

namespace Imdhemy\AppStore\Tests;

use DateTimeImmutable;
use Faker\Factory;
use Faker\Generator;
use JsonException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

/**
 * Faker Class
 * This class is used to generate fake data for testing purposes
 *
 * @mixin Generator
 */
class Faker
{
    private Generator $generator;

    /**
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Create a new Faker instance
     *
     * @param string $locale
     *
     * @return self
     */
    public static function create(string $locale = Factory::DEFAULT_LOCALE): self
    {
        return new self(Factory::create($locale));
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->generator->$name(...$arguments);
    }

    /**
     * @return string
     * @throws JsonException
     */
    public function signedPayload(): string
    {
        $contents = file_get_contents(__DIR__ . '/fixtures/payloads/test-notification-signed-payload.json');

        return json_decode($contents, true, 512, JSON_THROW_ON_ERROR)['signedPayload'];
    }

    /**
     * Creates a JWS of the given claims
     *
     * @param array $claims
     *
     * @return string
     */
    public function jwsOf(array $claims): string
    {
        $key = InMemory::file(__DIR__ . '/fixtures/keys/ecdsa-private.pem');

        return (new JwtFacade())->issue(
            new Sha256(),
            $key,
            static function (Builder $builder, DateTimeImmutable $issuedAt) use ($claims): Builder {
                foreach ($claims as $key => $value) {
                    $builder = $builder->withClaim($key, $value);
                }

                return $builder;
            }
        )->toString();
    }
}
