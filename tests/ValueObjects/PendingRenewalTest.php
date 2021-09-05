<?php

namespace Imdhemy\AppStore\Tests\ValueObjects;

use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\AutoRenewStatus;
use Imdhemy\AppStore\ValueObjects\ExpirationIntent;
use Imdhemy\AppStore\ValueObjects\PendingRenewal;
use Imdhemy\AppStore\ValueObjects\Time;

class PendingRenewalTest extends TestCase
{
    /**
     * @var array
     */
    private $commonAttributes;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->commonAttributes = [
            'auto_renew_product_id' => 'auto_renew_product_id',
            'original_transaction_id' => 'original_transaction_id',
            'product_id' => 'product_id',
        ];
    }

    /**
     * @test
     * - autoRenewProductId
     * - originalTransactionId
     * - productId
     */
    public function test_mandatory_attributes()
    {
        $attributes = $this->commonAttributes;
        $pendingRenewal = PendingRenewal::fromArray($attributes);

        $this->assertSame($pendingRenewal->getAutoRenewProductId(), $attributes['auto_renew_product_id']);
        $this->assertSame($pendingRenewal->getOriginalTransactionId(), $attributes['original_transaction_id']);
        $this->assertSame($pendingRenewal->getProductId(), $attributes['product_id']);
    }

    /**
     * @test
     */
    public function test_auto_renew_status()
    {
        $missingData = PendingRenewal::fromArray($this->commonAttributes);
        $this->assertNull($missingData->getAutoRenewStatus());

        $values = [AutoRenewStatus::TURNED_OFF, AutoRenewStatus::WILL_RENEW];
        $value = $values[array_rand($values)];

        $attributes = array_merge(
            $this->commonAttributes,
            ['auto_renew_status' => $value]
        );
        $pendingRenewal = PendingRenewal::fromArray($attributes);
        $this->assertSame($value, $pendingRenewal->getAutoRenewStatus());
    }

    /**
     * @test
     */
    public function test_expiration_intent()
    {
        $missingData = PendingRenewal::fromArray($this->commonAttributes);
        $this->assertNull($missingData->getExpirationIntent());

        $values = [
            ExpirationIntent::VOLUNTARY_CANCEL,
            ExpirationIntent::BILLING_ERROR,
            ExpirationIntent::DID_NOT_AGREE_PRICE_INCREASE,
            ExpirationIntent::PRODUCT_UNAVAILABLE,
            ExpirationIntent::UNKNOWN_ERROR,
        ];

        $value = $this->faker->randomElement($values);
        $attributes = array_merge($this->commonAttributes, ['expiration_intent' => $value]);
        $pendingRenewal = PendingRenewal::fromArray($attributes);

        $this->assertEquals($value, $pendingRenewal->getExpirationIntent());
    }

    /**
     * @test
     */
    public function test_grace_period_expires_date()
    {
        $missingData = PendingRenewal::fromArray($this->commonAttributes);
        $this->assertNull($missingData->getGracePeriodExpiresDate());

        $value = new Time(time() * 1000);
        $attributes = array_merge(
            $this->commonAttributes,
            ['grace_period_expires_date_ms' => $value->getCarbon()->getTimestampMs()]
        );
        $pendingRenewal = PendingRenewal::fromArray($attributes);
        $this->assertEquals($value, $pendingRenewal->getGracePeriodExpiresDate());
    }

    /**
     * @test
     */
    public function test_is_in_billing_retry_period()
    {
        $missingData = PendingRenewal::fromArray($this->commonAttributes);
        $this->assertNull($missingData->getIsInBillingRetryPeriod());

        $values = [PendingRenewal::STOPPED_ATTEMPTING_TO_RENEW, PendingRenewal::STILL_ATTEMPTING_TO_RENEW];
        $value = $values[array_rand($values)];
        $attributes = array_merge($this->commonAttributes, ['is_in_billing_retry_period' => $value]);
        $pendingRenewal = PendingRenewal::fromArray($attributes);
        $this->assertEquals($value, $pendingRenewal->getIsInBillingRetryPeriod());
    }

    /**
     * @test
     */
    public function test_offer_code_ref_name()
    {
        $missingData = PendingRenewal::fromArray($this->commonAttributes);
        $this->assertNull($missingData->getOfferCodeRefName());

        $value = 'offer_code_ref_name';
        $attributes = array_merge($this->commonAttributes, ['offer_code_ref_name' => 'offer_code_ref_name']);
        $pendingRenewal = PendingRenewal::fromArray($attributes);
        $this->assertSame($value, $pendingRenewal->getOfferCodeRefName());
    }

    /**
     * @test
     */
    public function test_price_consent_status()
    {
        $missingData = PendingRenewal::fromArray($this->commonAttributes);
        $this->assertNull($missingData->getPriceConsentStatus());

        $values = [PendingRenewal::PRICE_INCREASE_NOT_CONSENT, PendingRenewal::PRICE_INCREASE_CONSENT];
        $value = $this->faker->randomElement($values);
        $attributes = array_merge($this->commonAttributes, ['price_consent_status' => $value]);
        $pendingRenewal = PendingRenewal::fromArray($attributes);
        $this->assertSame($value, $pendingRenewal->getPriceConsentStatus());
    }

    /**
     * @test
     */
    public function test_promotional_offer_id()
    {
        $missingData = PendingRenewal::fromArray($this->commonAttributes);
        $this->assertNull($missingData->getPromotionalOfferId());

        $value = 'promotional_offer_id';
        $attributes = array_merge($this->commonAttributes, ['promotional_offer_id' => $value]);
        $pendingRenewal = PendingRenewal::fromArray($attributes);
        $this->assertSame($value, $pendingRenewal->getPromotionalOfferId());
    }
}
