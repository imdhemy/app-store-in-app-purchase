<?php

namespace Imdhemy\AppStore\Jws;

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser as JwtParser;

/**
 * Jws Parser class
 * This class is used to parse a JWS string into a Jws object
 */
class Parser implements JwsParser
{
    /**
     * @var JwtParser
     */
    private JwtParser $parser;

    /**
     * @param JwtParser $parser
     */
    public function __construct(JwtParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * A helper method to parse a JWS string into a Jws object
     *
     * @param $signedPayload
     *
     * @return JsonWebSignature
     */
    public static function toJws($signedPayload): JsonWebSignature
    {
        return (new self(new JwtParser(new JoseEncoder())))->parse($signedPayload);
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function parse(string $jws): JsonWebSignature
    {
        return Jws::fromJwtPlain($this->parser->parse($jws));
    }
}
