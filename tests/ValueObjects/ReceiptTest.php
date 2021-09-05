<?php

namespace Imdhemy\AppStore\Tests\ValueObjects;

use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\LatestReceiptInfo;
use Imdhemy\AppStore\ValueObjects\Receipt;

class ReceiptTest extends TestCase
{
    /**
     * @test
     */
    public function test_it_all_attributes_are_optional()
    {
        $this->assertInstanceOf(Receipt::class, new Receipt());
        $this->assertInstanceOf(Receipt::class, Receipt::fromArray([]));
    }

    /**
     * @test
     */
    public function test_adam_id()
    {
        $value = 123456789;
        $receipt = Receipt::fromArray(['adam_id' => $value]);
        $this->assertEquals($value, $receipt->getAdamId());
        $missingData = Receipt::fromArray([]);
        $this->assertNull($missingData->getAdamId());
    }

    /**
     * @test
     */
    public function test_app_item_id()
    {
        $value = 123456789;
        $receipt = Receipt::fromArray(['app_item_id' => $value]);
        $this->assertEquals($value, $receipt->getAppItemId());
        $this->assertNull(Receipt::fromArray([])->getAppItemId());
    }

    /**
     * @test
     */
    public function test_application_version()
    {
        $value = "13";
        $receipt = Receipt::fromArray(['application_version' => $value]);
        $this->assertEquals($value, $receipt->getApplicationVersion());
        $this->assertNull(Receipt::fromArray([])->getApplicationVersion());
    }

    /**
     * @test
     */
    public function test_bundle_id()
    {
        $value = "com.some.thing";
        $receipt = Receipt::fromArray(['bundle_id' => $value]);
        $this->assertEquals($value, $receipt->getBundleId());
        $this->assertNull(Receipt::fromArray([])->getBundleId());
    }

    /**
     * @test
     */
    public function test_download_id()
    {
        $value = 123456789;
        $receipt = Receipt::fromArray(['download_id' => $value]);
        $this->assertEquals($value, $receipt->getDownloadId());
        $this->assertNull(Receipt::fromArray([])->getDownloadId());
    }

    /**
     * @test
     */
    public function test_in_app()
    {
        $this->assertIsArray(Receipt::fromArray(['in_app' => []])->getInApp());
        $this->assertInstanceOf(
            LatestReceiptInfo::class,
            Receipt::fromArray([
                                   'in_app' => [
                                       [
                                           'original_transaction_id' => 'original_transaction_id',
                                           'product_id' => 'product_id',
                                           'quantity' => '1',
                                           'transaction_id' => 'transaction_id',
                                       ],
                                   ],
                               ])->getInApp()[0]
        );
        $this->assertNull(Receipt::fromArray([])->getInApp());
    }

    /**
     * @test
     */
    public function test_original_purchase_date()
    {
        $value = 1617375688000;
        $receipt = Receipt::fromArray(['original_purchase_date_ms' => $value]);
        $this->assertEquals(
            $value,
            $receipt->getOriginalPurchaseDate()->getCarbon()->getTimestampMs()
        );
        $this->assertNull(Receipt::fromArray([])->getOriginalPurchaseDate());
    }

    /**
     * @test
     */
    public function test_receipt_creation_date()
    {
        $value = 1617375688000;
        $receipt = Receipt::fromArray(['receipt_creation_date_ms' => $value]);
        $this->assertEquals(
            $value,
            $receipt->getReceiptCreationDate()->getCarbon()->getTimestampMs()
        );
        $this->assertNull(Receipt::fromArray([])->getReceiptCreationDate());
    }

    /**
     * @test
     */
    public function test_receipt_type()
    {
        $values = [
            Receipt::RECEIPT_TYPE_PRODUCTION,
            Receipt::RECEIPT_TYPE_PRODUCTION_VPP,
            Receipt::RECEIPT_TYPE_PRODUCTION_VPP_SANDBOX,
            Receipt::RECEIPT_TYPE_SANDBOX,
        ];
        $value = $this->faker->randomElement($values);
        $receipt = Receipt::fromArray(['receipt_type' => $value]);
        $this->assertEquals($value, $receipt->getReceiptType());
        $this->assertNull(Receipt::fromArray([])->getReceiptType());
    }

    /**
     * @test
     */
    public function test_request_date()
    {
        $value = 1617375688000;
        $receipt = Receipt::fromArray(['request_date_ms' => $value]);
        $this->assertEquals(
            $value,
            $receipt->getRequestDate()->getCarbon()->getTimestampMs()
        );
        $this->assertNull(Receipt::fromArray([])->getRequestDate());
    }

    /**
     * @test
     */
    public function test_version_external_identifier()
    {
        $value = 1234567;
        $receipt = Receipt::fromArray(['version_external_identifier' => $value]);
        $this->assertEquals($value, $receipt->getVersionExternalIdentifier());
        $this->assertNull(Receipt::fromArray([])->getVersionExternalIdentifier());
    }

    /**
     * @test
     */
    public function test_original_application_version()
    {
        $value = '1.0';
        $receipt = Receipt::fromArray(['original_application_version' => $value]);
        $this->assertEquals($value, $receipt->getOriginalApplicationVersion());
        $this->assertNull(Receipt::fromArray([])->getOriginalApplicationVersion());
    }

    /**
     * @test
     */
    public function test_expiration_date()
    {
        $value = 1617375688000;
        $receipt = Receipt::fromArray(['expiration_date_ms' => $value]);
        $this->assertEquals(
            $value,
            $receipt->getExpirationDate()->getCarbon()->getTimestampMs()
        );
        $this->assertNull(Receipt::fromArray([])->getExpirationDate());
    }

    /**
     * @test
     */
    public function test_pre_order_date()
    {
        $value = 1617375688000;
        $receipt = Receipt::fromArray(['preorder_date_ms' => $value]);
        $this->assertEquals(
            $value,
            $receipt->getPreOrderDate()->getCarbon()->getTimestampMs()
        );
        $this->assertNull(Receipt::fromArray([])->getPreOrderDate());
    }
}
