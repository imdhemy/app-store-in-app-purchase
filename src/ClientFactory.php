<?php

namespace Imdhemy\AppStore;

use ArrayAccess;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ClientFactory
 */
class ClientFactory
{
    public const BASE_URI = 'https://buy.itunes.apple.com';
    public const BASE_URI_SANDBOX = 'https://sandbox.itunes.apple.com';

    public const STORE_KIT_PRODUCTION_URI = 'https://api.storekit.itunes.apple.com';
    public const STORE_KIT_SANDBOX_URI = 'https://api.storekit-sandbox.itunes.apple.com';

    /**
     * @param bool $sandbox
     * @param array $options
     *
     * @return ClientInterface
     */
    public static function create(bool $sandbox = false, array $options = []): ClientInterface
    {
        if (empty($options['base_uri'])) {
            $options['base_uri'] = $sandbox ? self::BASE_URI_SANDBOX : self::BASE_URI;
        }

        return new Client($options);
    }

    /**
     * @return ClientInterface
     */
    public static function createSandbox(): ClientInterface
    {
        return self::create(true);
    }

    /**
     * Creates a client that returns the specified response
     *
     * @param ResponseInterface $responseMock
     * @param array $transactions
     *
     * @psalm-suppress ReferenceConstraintViolation
     * @return ClientInterface
     */
    public static function mock(ResponseInterface $responseMock, array &$transactions = []): ClientInterface
    {
        $mockHandler = new MockHandler([$responseMock]);
        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push(Middleware::history($transactions));

        return new Client(['handler' => $handlerStack]);
    }

    /**
     * Creates a client that returns the specified array of responses in queue order
     *
     * @param array<int, ResponseInterface|RequestExceptionInterface> $responseQueue
     * @param array|ArrayAccess<int, array> $container Container to hold the history (by reference).
     * @return ClientInterface
     */
    public static function mockQueue(array $responseQueue, array|ArrayAccess &$container = []): ClientInterface
    {
        $mockHandler = new MockHandler($responseQueue);
        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push(Middleware::history($container));

        return new Client(['handler' => $handlerStack]);
    }

    /**
     * Creates a client that returns the specified request exception
     *
     * @param RequestExceptionInterface $error
     * @param array $transactions
     *
     * @psalm-suppress ReferenceConstraintViolation
     * @return ClientInterface
     */
    public static function mockError(RequestExceptionInterface $error, array &$transactions = []): ClientInterface
    {
        $mockHandler = new MockHandler([$error]);
        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push(Middleware::history($transactions));

        return new Client(['handler' => $handlerStack]);
    }
}
