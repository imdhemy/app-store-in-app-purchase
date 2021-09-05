<?php

namespace Imdhemy\AppStore\Tests\ValueObjects;

use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\Cancellation;
use Imdhemy\AppStore\ValueObjects\LatestReceiptInfo;
use Imdhemy\AppStore\ValueObjects\Time;

class LatestReceiptInfoTest extends TestCase
{
    /**
     * @var string[]
     */
    private $commonAttributes;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->commonAttributes = [
            "quantity" => "1",
            "product_id" => "fake_product_id",
            "transaction_id" => "fake_transaction_id",
            "original_transaction_id" => "fake_original_transaction_id",
        ];
    }

    /**
     * @test
     */
    public function test_required_attributes()
    {
        $quantity = "1";
        $productId = "fake_product_id";
        $transactionId = "fake_transaction_id";
        $originalTransactionId = "fake_original_transaction_id";

        $latestReceiptInfo = LatestReceiptInfo::fromArray($this->commonAttributes);

        $this->assertEquals($quantity, $latestReceiptInfo->getQuantity());
        $this->assertSame($productId, $latestReceiptInfo->getProductId());
        $this->assertSame($transactionId, $latestReceiptInfo->getTransactionId());
        $this->assertSame($originalTransactionId, $latestReceiptInfo->getOriginalTransactionId());
    }

    /**
     * @test
     */
    public function test_app_account_token()
    {
        $value = $this->faker->uuid();
        $attributes = array_merge($this->commonAttributes, ['app_account_token' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals($value, $latestReceiptInfo->getAppAccountToken());
    }

    /**
     * @test
     */
    public function test_cancellation_date_ms()
    {
        $value = $this->faker->unixTime() * 1000;
        $attributes = array_merge($this->commonAttributes, ['cancellation_date_ms' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals(new Time($value), $latestReceiptInfo->getCancellationDate());
    }

    /**
     * @test
     */
    public function test_cancellation_reason()
    {
        $reasons = [Cancellation::REASON_OTHER, Cancellation::REASON_APP_ISSUE];
        $value = $this->faker->randomElement($reasons);
        $attributes = array_merge($this->commonAttributes, ['cancellation_reason' => (string)$value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals($value, $latestReceiptInfo->getCancellationReason());
    }

    /**
     * @test
     */
    public function test_expires_date_ms()
    {
        $value = $this->faker->unixTime() * 1000;
        $attributes = array_merge($this->commonAttributes, ['expires_date_ms' => $value]);
        $latestReceipt = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals(new Time($value), $latestReceipt->getExpiresDate());
    }

    /**
     * @test
     */
    public function test_in_app_ownership_type()
    {
        $types = [
            LatestReceiptInfo::OWNERSHIP_TYPE_FAMILY_SHARED,
            LatestReceiptInfo::OWNERSHIP_TYPE_PURCHASED,
        ];
        $value = $this->faker->randomElement($types);
        $attributes = array_merge($this->commonAttributes, ['in_app_ownership_type' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);
        $this->assertSame($value, $latestReceiptInfo->getInAppOwnershipType());
    }

    /**
     * @test
     */
    public function test_is_in_intro_offer_period()
    {
        $values = ['true', true, 'false', false];
        $value = $this->faker->randomElement($values);
        $attributes = array_merge($this->commonAttributes, ['is_in_intro_offer_period' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);

        if ($value === 'false' || $value === false) {
            $this->assertFalse($latestReceiptInfo->getIsInIntroOfferPeriod());
        }

        if ($value === 'true' || $value === true) {
            $this->assertTrue($latestReceiptInfo->getIsInIntroOfferPeriod());
        }
    }

    /**
     * @test
     */
    public function test_is_trial_period()
    {
        $values = ['true', true, 'false', false];
        $value = $this->faker->randomElement($values);
        $attributes = array_merge($this->commonAttributes, ['is_trial_period' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);

        if ($value === 'false' || $value === false) {
            $this->assertFalse($latestReceiptInfo->getIsTrialPeriod());
        }

        if ($value === 'true' || $value === true) {
            $this->assertTrue($latestReceiptInfo->getIsTrialPeriod());
        }
    }

    /**
     * @test
     */
    public function test_is_upgraded()
    {
        $values = ['true', true];
        $value = $this->faker->randomElement($values);
        $attributes = array_merge($this->commonAttributes, ['is_upgraded' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);

        if ($value === 'false' || $value === false) {
            $this->assertFalse($latestReceiptInfo->getIsUpgraded());
        }

        if ($value === 'true' || $value === true) {
            $this->assertTrue($latestReceiptInfo->getIsUpgraded());
        }
    }

    /**
     * @test
     */
    public function test_offer_code_ref_name()
    {
        $value = 'fake_offer_code_ref_name';
        $attributes = array_merge($this->commonAttributes, ['offer_code_ref_name' => $value]);
        $latestReceipt = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals($value, $latestReceipt->getOfferCodeRefName());
    }

    /**
     * @test
     */
    public function test_original_purchase_date_ms()
    {
        $value = $this->faker->unixTime() * 1000;
        $attributes = array_merge($this->commonAttributes, ['original_purchase_date_ms' => $value]);
        $latestReceipt = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals(new Time($value), $latestReceipt->getOriginalPurchaseDate());
    }

    /**
     * @test
     */
    public function test_promotional_offer_id()
    {
        $value = 'fake_promotional_offer_id';
        $attributes = array_merge($this->commonAttributes, ['promotional_offer_id' => $value]);
        $latestReceipt = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals($value, $latestReceipt->getPromotionalOfferId());
    }

    /**
     * @test
     */
    public function test_purchase_date_ms()
    {
        $value = $this->faker->unixTime() * 1000;
        $attributes = array_merge($this->commonAttributes, ['purchase_date_ms' => $value]);
        $latestReceipt = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals(new Time($value), $latestReceipt->getPurchaseDate());
    }

    /**
     * @test
     */
    public function test_missing_data_is_null()
    {
        $notNullGetters = [
            'getQuantity',
            'getProductId',
            'getTransactionId',
            'getOriginalTransactionId',
        ];

        $latestReceiptInfo = LatestReceiptInfo::fromArray($this->commonAttributes);
        $getters = array_filter(get_class_methods($latestReceiptInfo), function (string $method) use ($notNullGetters) {
            $isGetter = strpos($method, 'get') !== false;
            $isNullGetter = ! in_array($method, $notNullGetters);

            return $isGetter && $isNullGetter;
        });

        foreach ($getters as $getter) {
            $this->assertNull($latestReceiptInfo->$getter());
        }
    }
}
