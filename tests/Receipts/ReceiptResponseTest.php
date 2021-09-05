<?php

namespace Imdhemy\AppStore\Tests\Receipts;

use Exception;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\ValueObjects\LatestReceiptInfo;
use Imdhemy\AppStore\ValueObjects\PendingRenewal;
use Imdhemy\AppStore\ValueObjects\Receipt;
use Imdhemy\AppStore\ValueObjects\Status;
use PHPUnit\Framework\TestCase;

class ReceiptResponseTest extends TestCase
{
    /**
     * @test
     */
    public function test_all_attributes_are_optional_except_status()
    {
        $response = new ReceiptResponse(0);
        $this->assertInstanceOf(ReceiptResponse::class, $response);
    }

    /**
     * @test
     */
    public function test_environment_attribute()
    {
        $environments = [ReceiptResponse::ENV_PRODUCTION, ReceiptResponse::ENV_SANDBOX];

        $body = [
            'status' => 0,
            'environment' => $environments[array_rand($environments)],
        ];

        $response = ReceiptResponse::fromArray($body);
        $this->assertEquals($body['environment'], $response->getEnvironment());

        $missingEnvResponse = ReceiptResponse::fromArray(['status' => 0]);
        $this->assertNull($missingEnvResponse->getEnvironment());
    }

    /**
     * @test
     * @throws Exception
     */
    public function test_is_retryable()
    {
        $value = random_int(0, 1);
        $body = ['is-retryable' => $value, 'status' => 0];

        $response = ReceiptResponse::fromArray($body);
        $this->assertEquals((bool)$value, $response->getIsRetryable());

        $missingData = ReceiptResponse::fromArray(['status' => 0]);
        $this->assertNull($missingData->getIsRetryable());
    }

    /**
     * @test
     */
    public function test_latest_receipt()
    {
        $value = base64_decode("fake_receipt_content");
        $response = ReceiptResponse::fromArray(['latest_receipt' => $value, 'status' => 0]);

        $this->assertEquals($value, $response->getLatestReceipt());

        $missingData = ReceiptResponse::fromArray(['status' => 0]);
        $this->assertNull($missingData->getLatestReceipt());
    }

    /**
     * @test
     */
    public function test_latest_receipt_info()
    {
        $value = [];
        $response = ReceiptResponse::fromArray(['latest_receipt_info' => $value, 'status' => 0]);

        $this->assertIsArray($response->getLatestReceiptInfo());
        $this->assertEmpty($response->getLatestReceiptInfo());

        $valueWithSingleObject = [
            [
                'original_transaction_id' => 'original_transaction_id',
                'product_id' => 'product_id',
                'quantity' => '1',
                'transaction_id' => 'transaction_id',
            ],
        ];
        $response = ReceiptResponse::fromArray(['latest_receipt_info' => $valueWithSingleObject, 'status' => 0]);
        $this->assertInstanceOf(LatestReceiptInfo::class, $response->getLatestReceiptInfo()[0]);

        $missingData = ReceiptResponse::fromArray(['status' => 0]);
        $this->assertNull($missingData->getLatestReceiptInfo());
    }

    /**
     * @test
     */
    public function test_pending_renewal_info()
    {
        $value = [];
        $response = ReceiptResponse::fromArray(['pending_renewal_info' => $value, 'status' => 0]);

        $this->assertIsArray($response->getPendingRenewalInfo());
        $this->assertEmpty($response->getPendingRenewalInfo());

        $valueWithSingleObject = [
            [
                'auto_renew_product_id' => 'auto_renew_product_id',
                'original_transaction_id' => 'original_transaction_id',
                'product_id' => 'product_id',
            ],
        ];

        $response = ReceiptResponse::fromArray(['pending_renewal_info' => $valueWithSingleObject, 'status' => 0]);
        $this->assertInstanceOf(PendingRenewal::class, $response->getPendingRenewalInfo()[0]);

        $missingData = ReceiptResponse::fromArray(['status' => 0]);
        $this->assertNull($missingData->getPendingRenewalInfo());
    }

    /**
     * @test
     */
    public function test_receipt()
    {
        $value = [];
        $response = ReceiptResponse::fromArray(['receipt' => $value, 'status' => 0]);

        $this->assertInstanceOf(Receipt::class, $response->getReceipt());

        $missingData = ReceiptResponse::fromArray(['status' => 0]);
        $this->assertNull($missingData->getReceipt());
    }

    /**
     * @test
     */
    public function test_status()
    {
        $statusList = array_merge([0], range(21000, 21010));
        $value = $statusList[array_rand($statusList)];

        $response = ReceiptResponse::fromArray(['status' => $value,]);
        $this->assertInstanceOf(Status::class, $response->getStatus());
        $this->assertEquals($value, $response->getStatus()->getValue());
    }
}
