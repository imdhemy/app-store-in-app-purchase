<?php

namespace Imdhemy\AppStore\Tests\Unit;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Tests\TestCase;
use ReflectionClass;

final class ClientFactoryTest extends TestCase
{
    /**
     * @test
     * @psalm-suppress MixedArrayAccess
     */
    public function create_client(): void
    {
        $client = ClientFactory::create();

        $baseUri = (string)(new ReflectionClass($client))->getProperty('config')->getValue($client)['base_uri'];
        $this->assertSame('https://buy.itunes.apple.com', $baseUri);
    }

    /**
     * @test
     * @psalm-suppress MixedArrayAccess
     */
    public function create_sandbox_client(): void
    {
        $client = ClientFactory::createSandbox();

        $baseUri = (string)(new ReflectionClass($client))->getProperty('config')->getValue($client)['base_uri'];
        $this->assertSame('https://sandbox.itunes.apple.com', $baseUri);
    }

    /**
     * @test
     */
    public function client_response_can_be_mocked(): void
    {
        $statusCode = 200;
        $body = 'This is a mock!';
        $mock = new Response($statusCode, [], $body);

        $client = ClientFactory::mock($mock);
        $response = $client->request('GET', '/');

        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertEquals($body, (string)$response->getBody());
    }

    /**
     * @test
     */
    public function a_queue_of_responses_can_be_mocked(): void
    {
        $mocks = [
            new Response(200, [], 'first'),
            new Response(201, [], 'second'),
        ];
        $client = ClientFactory::mockQueue($mocks);

        $firstResponse = $client->request('GET', '/');
        $this->assertEquals(200, $firstResponse->getStatusCode());
        $this->assertEquals('first', (string)$firstResponse->getBody());

        $secondResponse = $client->request('POST', '/');
        $this->assertEquals(201, $secondResponse->getStatusCode());
        $this->assertEquals('second', (string)$secondResponse->getBody());
    }

    /**
     * @test
     */
    public function it_can_mock_an_error_response(): void
    {
        $message = 'Something went wrong';

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage($message);

        $error = new RequestException(
            $message,
            new Request('GET', '/admin'),
            new Response(403, [], 'Forbidden')
        );
        $client = ClientFactory::mockError($error);

        $client->request('GET', '/admin');
    }

    /**
     * @test
     * @psalm-suppress MixedArrayAccess
     */
    public function mock_can_track_transactions(): void
    {
        $transactions = [];
        $response = new Response();

        $client = ClientFactory::mock($response, $transactions);
        $client->request('GET', '/mock');

        $transactionResponse = $transactions[0]['response'];
        $this->assertSame($response, $transactionResponse);
    }

    /**
     * @test
     * @psalm-suppress MixedArrayAccess
     */
    public function mock_queue_can_track_transactions(): void
    {
        $size = random_int(1, 10);
        $queue = [];

        for ($i = 0; $i < $size; $i++) {
            $queue[] = new Response(200, [], sprintf('Response #%d', $i));
        }

        $transactions = [];
        $client = ClientFactory::mockQueue($queue, $transactions);

        for ($i = 0; $i < $size; $i++) {
            $client->request('GET', sprintf('/foo-%s', $i));
        }

        for ($i = 0; $i < $size; $i++) {
            $expectedResponse = $queue[$i];
            $transactionResponse = $transactions[$i]['response'];
            $this->assertSame($expectedResponse, $transactionResponse);
        }
    }

    /**
     * @test
     */
    public function mock_error_can_track_transactions(): void
    {
        $transactions = [];
        $request = new Request('GET', '/admin');
        $response = new Response(403, [], 'Forbidden');

        $error = new RequestException('Something went wrong', $request, $response);
        $client = ClientFactory::mockError($error, $transactions);

        try {
            $client->get('/admin');
        } catch (RequestException $exception) {
            $error = $transactions[0]['error'];
            $this->assertSame($exception, $error);
        }
    }
}
