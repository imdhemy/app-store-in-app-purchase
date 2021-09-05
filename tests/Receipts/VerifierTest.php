<?php

namespace Imdhemy\AppStore\Tests\Receipts;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\Receipts\Verifier;
use Imdhemy\AppStore\Tests\TestCase;

class VerifierTest extends TestCase
{
    /**
     * @test
     * @throws GuzzleException
     * @throws InvalidReceiptException
     */
    public function test_verify_subscription()
    {
        // Given
        $body = $this->getVerifyReceiptResponse();
        $expectedResponse = new Response(200, [], $body);
        $transactions = [];
        $client = ClientFactory::mock($expectedResponse, $transactions);

        $subscriptionReceiptContent = $this->getSubscriptionReceipt();
        $iosReceipt = json_decode($subscriptionReceiptContent, true);

        $password = "fake_password";
        $receiptData = $iosReceipt['transactionReceipt'];
        $verifier = new Verifier($client, $receiptData, $password);

        // when
        $receiptResponse = $verifier->verify();

        // then
        $this->assertInstanceOf(ReceiptResponse::class, $receiptResponse);
        $this->assertCount(1, $transactions);

        /** @var Request $transactionRequest */
        $transactionRequest = $transactions[0]['request'];
        $this->assertEquals('POST', $transactionRequest->getMethod());
        $this->assertEquals(Verifier::VERIFY_RECEIPT_PATH, $transactionRequest->getUri()->getPath());

        /** @var Response $transactionResponse */
        $transactionResponse = $transactions[0]['response'];
        $this->assertSame($expectedResponse, $transactionResponse);
    }

    /**
     * @test
     * @throws GuzzleException
     * @throws InvalidReceiptException
     */
    public function test_verify_subscription_on_test_environment()
    {
        // Given
        $testEnvBody = $this->getVerifyReceiptResponse(['status' => Verifier::TEST_ENV_CODE]);
        $testEnvResponse = new Response(200, [], $testEnvBody);

        $body = $this->getVerifyReceiptResponse();
        $expectedResponse = new Response(200, [], $body);
        $transactions = [];

        $client = ClientFactory::mockQueue([$testEnvResponse, $expectedResponse], $transactions);

        $subscriptionReceiptContent = $this->getSubscriptionReceipt();
        $iosReceipt = json_decode($subscriptionReceiptContent, true);

        $password = "fake_password";
        $receiptData = $iosReceipt['transactionReceipt'];
        $verifier = new Verifier($client, $receiptData, $password);

        // when
        $receiptResponse = $verifier->verifyRenewable($client);

        // then
        $this->assertInstanceOf(ReceiptResponse::class, $receiptResponse);
        $this->assertCount(2, $transactions);

        /** @var Request $transactionRequest */
        $transactionRequest = $transactions[0]['request'];
        $this->assertEquals('POST', $transactionRequest->getMethod());
        $this->assertEquals(Verifier::VERIFY_RECEIPT_PATH, $transactionRequest->getUri()->getPath());

        $this->assertSame($testEnvResponse, $transactions[0]['response']);
        $this->assertSame($expectedResponse, $transactions[1]['response']);
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function test_it_throws_exception_on_invalid_responses()
    {
        $this->expectException(InvalidReceiptException::class);

        $receiptData = base64_encode('fake_receipt_data');
        $client = ClientFactory::createSandbox();

        (new Verifier($client, $receiptData, ''))->verifyRenewable();
    }
}
