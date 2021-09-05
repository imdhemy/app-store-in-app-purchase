<?php

namespace Imdhemy\AppStore\ValueObjects;

/**
 * PendingRenewal Class
 * Refers to open or failed auto-renewable subscription renewals
 * @see https://developer.apple.com/documentation/appstorereceipts/responsebody/pending_renewal_info
 */
final class PendingRenewal
{
    public const STILL_ATTEMPTING_TO_RENEW = 1;
    public const STOPPED_ATTEMPTING_TO_RENEW = 0;

    public const PRICE_INCREASE_CONSENT = "1";
    public const PRICE_INCREASE_NOT_CONSENT = "0";

    /**
     * The value for this key corresponds to the productIdentifier property
     * of the product that the customer’s subscription renews.
     * @see https://developer.apple.com/documentation/storekit/skpayment/1506155-productidentifier
     * @var string
     */
    private $autoRenewProductId;

    /**
     * The current renewal status for the auto-renewable subscription.
     * @see AutoRenewStatus
     * @see https://developer.apple.com/documentation/appstorereceipts/auto_renew_status?changes=latest_minor
     * @var int|null
     */
    private $autoRenewStatus;

    /**
     * The reason a subscription expired.
     * This field is only present for a receipt that contains an expired auto-renewable subscription
     * @see https://developer.apple.com/documentation/appstorereceipts/expiration_intent
     * @see ExpirationIntent
     * @var int|null
     */
    private $expirationIntent;

    /**
     * The time at which the grace period for subscription renewals expires,
     * in UNIX epoch time format, in milliseconds
     * @var int|null
     */
    private $gracePeriodExpiresDate;

    /**
     * A flag that indicates Apple is attempting to renew an expired subscription automatically.
     * @see https://developer.apple.com/documentation/appstorereceipts/is_in_billing_retry_period?changes=latest_minor
     * @var int|null
     */
    private $isInBillingRetryPeriod;

    /**
     * The reference name of a subscription offer that you configured in App Store Connect.
     * This field is present when a customer redeemed a subscription offer code.
     * @var string|null
     * @see https://developer.apple.com/documentation/appstorereceipts/offer_code_ref_name?changes=latest_minor
     */
    private $offerCodeRefName;

    /**
     * The transaction identifier of the original purchase.
     * @see https://developer.apple.com/documentation/appstorereceipts/original_transaction_id?changes=latest_minor
     * @var string
     */
    private $originalTransactionId;

    /**
     * The price consent status for a subscription price increase.
     * This field is only present if the customer was notified of the price increase.
     * The default value is "0" and changes to "1" if the customer consents.
     * ->-> Based on values, it should be integer, but Apple documentation
     * describes this key string.
     * @var string|null
     */
    private $priceConsentStatus;

    /**
     * The unique identifier of the product purchased.
     * You provide this value when creating the product in App Store Connect,
     * and it corresponds to the productIdentifier property of the SKPayment object
     * stored in the transaction's payment property.
     * @see https://developer.apple.com/documentation/storekit/skpayment?changes=latest_minor
     * @var string
     */
    private $productId;

    /**
     * The identifier of the promotional offer for an auto-renewable subscription
     * that the user redeemed. You provide this value in the Promotional Offer Identifier
     * field when you create the promotional offer in App Store Connect.
     * @see https://developer.apple.com/documentation/appstorereceipts/promotional_offer_id
     * @var string|null
     */
    private $promotionalOfferId;

    /**
     * @param string $autoRenewProductId
     * @param string $originalTransactionId
     * @param string $productId
     */
    public function __construct(string $autoRenewProductId, string $originalTransactionId, string $productId)
    {
        $this->autoRenewProductId = $autoRenewProductId;
        $this->originalTransactionId = $originalTransactionId;
        $this->productId = $productId;
    }

    /**
     * @param array $attributes
     * @return static
     */
    public static function fromArray(array $attributes): self
    {
        $obj = new self(
            $attributes['auto_renew_product_id'],
            $attributes['original_transaction_id'],
            $attributes['product_id']
        );

        $obj->autoRenewStatus = $attributes['auto_renew_status'] ?? null;
        $obj->expirationIntent = $attributes['expiration_intent'] ?? null;
        $obj->gracePeriodExpiresDate = $attributes['grace_period_expires_date_ms'] ?? null;
        $obj->isInBillingRetryPeriod = $attributes['is_in_billing_retry_period'] ?? null;
        $obj->offerCodeRefName = $attributes['offer_code_ref_name'] ?? null;
        $obj->priceConsentStatus = $attributes['price_consent_status'] ?? null;
        $obj->promotionalOfferId = $attributes['promotional_offer_id'] ?? null;

        return $obj;
    }


    /**
     * @return int|null
     */
    public function getIsInBillingRetryPeriod(): ?int
    {
        return $this->isInBillingRetryPeriod;
    }

    /**
     * @return string
     */
    public function getAutoRenewProductId(): string
    {
        return $this->autoRenewProductId;
    }

    /**
     * @return string
     */
    public function getOriginalTransactionId(): string
    {
        return $this->originalTransactionId;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @return Time|null
     */
    public function getGracePeriodExpiresDate(): ?Time
    {
        return
            is_null($this->gracePeriodExpiresDate) ?
                null :
                new Time($this->gracePeriodExpiresDate);
    }

    /**
     * @return string|null
     */
    public function getOfferCodeRefName(): ?string
    {
        return $this->offerCodeRefName;
    }

    /**
     * @return int|null
     */
    public function getExpirationIntent(): ?int
    {
        return $this->expirationIntent;
    }

    /**
     * @return int|null
     */
    public function getAutoRenewStatus(): ?int
    {
        return $this->autoRenewStatus;
    }

    /**
     * @return string|null
     */
    public function getPriceConsentStatus(): ?string
    {
        return $this->priceConsentStatus;
    }

    /**
     * @return string|null
     */
    public function getPromotionalOfferId(): ?string
    {
        return $this->promotionalOfferId;
    }
}
