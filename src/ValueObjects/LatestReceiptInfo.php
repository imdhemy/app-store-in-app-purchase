<?php

namespace Imdhemy\AppStore\ValueObjects;

/**
 * LatestReceiptInfo class which contains in-app purchase transaction
 * @link https://developer.apple.com/documentation/appstorereceipts/responsebody/latest_receipt_info
 */
final class LatestReceiptInfo
{
    /**
     * @const string The transaction belongs to a family member who benefits from service.
     */
    public const OWNERSHIP_TYPE_FAMILY_SHARED = 'FAMILY_SHARED';

    /**
     * @const  string The transaction belongs to the purchaser.
     */
    public const OWNERSHIP_TYPE_PURCHASED = 'PURCHASED';

    /**
     * @const string true string value
     */
    private const TRUE = 'true';

    /**
     * A UUID that associates the transaction with a user on your own service.
     * This field is only present if your app supplied an appAccountToken(_:)
     * when the user made the purchase; it’s only present in the sandbox environment.
     * @see https://developer.apple.com/documentation/storekit/transaction/3749684-appaccounttoken?changes=latest_minor
     * @see https://developer.apple.com/documentation/storekit/product/purchaseoption/3749440-appaccounttoken?changes=latest_minor
     * @var string|null
     */
    private $appAccountToken;

    /**
     * @see \Imdhemy\AppStore\ValueObjects\Cancellation::$time
     * @var int|null
     */
    private $cancellationDate;

    /**
     * @see \Imdhemy\AppStore\ValueObjects\Cancellation::$reason
     * @var int|null
     */
    private $cancellationReason;

    /**
     * The time a subscription expires or when it will renew,
     * in UNIX epoch time format, in milliseconds.
     * @var int|null
     */
    private $expiresDate;

    /**
     * The relationship of the user with the family-shared purchase to which they have access.
     * @see https://developer.apple.com/documentation/appstorereceipts/in_app_ownership_type?changes=latest_minor
     * @var string|null
     */
    private $inAppOwnershipType;

    /**
     * An indicator of whether an auto-renewable subscription is in the introductory price period.
     * @see https://developer.apple.com/documentation/appstorereceipts/is_in_intro_offer_period?changes=latest_minor
     * @var string|bool|null
     */
    private $isInIntroOfferPeriod;

    /**
     * An indicator of whether an auto-renewable subscription is in the free trial period.
     * @see https://developer.apple.com/documentation/appstorereceipts/is_trial_period?changes=latest_minor
     * @var string|bool|null
     */
    private $isTrialPeriod;

    /**
     * An indicator that a subscription has been canceled due to an upgrade.
     * This field is only present for upgrade transactions.
     * @var bool|string|null
     */
    private $isUpgraded;

    /**
     * The offer-reference name of the subscription offer code that the customer redeemed.
     * @see https://developer.apple.com/documentation/appstorereceipts/offer_code_ref_name?changes=latest_minor
     * @var string|null
     */
    private $offerCodeRefName;

    /**
     * The time of the original app purchase, in UNIX epoch time format, in milliseconds.
     * @var int|null
     */
    private $originalPurchaseDate;

    /**
     * The transaction identifier of the original purchase.
     * @see https://developer.apple.com/documentation/appstorereceipts/original_transaction_id?changes=latest_minor
     * @var string
     */
    private $originalTransactionId;

    /**
     * The unique identifier of the product purchased.
     * @var string
     */
    private $productId;

    /**
     * The identifier of the subscription offer redeemed by the user.
     * @see https://developer.apple.com/documentation/appstorereceipts/promotional_offer_id?changes=latest_minor
     * @var string|null
     */
    private $promotionalOfferId;

    /**
     * The time the App Store charged the user’s account for a purchased or restored product
     * @var int|null
     */
    private $purchaseDate;

    /**
     * The number of consumable products purchased.
     * @var int
     */
    private $quantity;

    /**
     * The identifier of the subscription group to which the subscription belongs.
     * @see https://developer.apple.com/documentation/storekit/skproduct/2981047-subscriptiongroupidentifier?changes=latest_minor
     * @var string|null
     */
    private $subscriptionGroupIdentifier;

    /**
     * A unique identifier for purchase events across devices, including subscription-renewal events.
     * This value is the primary key for identifying subscription purchases.
     * @var string|null
     */
    private $webOrderLineItemId;

    /**
     * A unique identifier for a transaction such as a purchase, restore, or renewal.
     * @see https://developer.apple.com/documentation/appstorereceipts/transaction_id?changes=latest_minor
     * @var string
     */
    private $transactionId;

    /**
     * @param string $originalTransactionId
     * @param string $productId
     * @param int $quantity
     * @param string $transactionId
     */
    public function __construct(string $originalTransactionId, string $productId, int $quantity, string $transactionId)
    {
        $this->originalTransactionId = $originalTransactionId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->transactionId = $transactionId;
    }

    /**
     * @param array $attributes
     * @return static
     */
    public static function fromArray(array $attributes): self
    {
        $obj = new self(
            $attributes['original_transaction_id'],
            $attributes['product_id'],
            $attributes['quantity'],
            $attributes['transaction_id']
        );

        $obj->appAccountToken = $attributes['app_account_token'] ?? null;
        $obj->cancellationDate = $attributes['cancellation_date_ms'] ?? null;
        $obj->cancellationReason = $attributes['cancellation_reason'] ?? null;
        $obj->expiresDate = $attributes['expires_date_ms'] ?? null;
        $obj->inAppOwnershipType = $attributes['in_app_ownership_type'] ?? null;
        $obj->isInIntroOfferPeriod = $attributes['is_in_intro_offer_period'] ?? null;
        $obj->isTrialPeriod = $attributes['is_trial_period'] ?? null;
        $obj->isUpgraded = $attributes['is_upgraded'] ?? null;
        $obj->offerCodeRefName = $attributes['offer_code_ref_name'] ?? null;
        $obj->originalPurchaseDate = $attributes['original_purchase_date_ms'] ?? null;
        $obj->promotionalOfferId = $attributes['promotional_offer_id'] ?? null;
        $obj->purchaseDate = $attributes['purchase_date_ms'] ?? null;
        $obj->subscriptionGroupIdentifier = $attributes['subscription_group_identifier'] ?? null;
        $obj->webOrderLineItemId = $attributes['web_order_line_item_id'] ?? null;

        return $obj;
    }

    /**
     * @return Time|null
     */
    public function getExpiresDate(): ?Time
    {
        return
            $this->expiresDate ?
                new Time($this->expiresDate) :
                null;
    }

    /**
     * @return Time|null
     */
    public function getOriginalPurchaseDate(): ?Time
    {
        return
            $this->originalPurchaseDate ?
                new Time($this->originalPurchaseDate) :
                null;
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
    public function getPurchaseDate(): ?Time
    {
        return
            $this->purchaseDate ?
                new Time($this->purchaseDate) :
                null;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return string|null
     */
    public function getSubscriptionGroupIdentifier(): ?string
    {
        return $this->subscriptionGroupIdentifier;
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * @return string|null
     */
    public function getWebOrderLineItemId(): ?string
    {
        return $this->webOrderLineItemId;
    }

    /**
     * @return Time|null
     */
    public function getCancellationDate(): ?Time
    {
        return
            $this->cancellationDate ?
                new Time($this->cancellationDate) :
                null;
    }

    /**
     * @return int|null
     */
    public function getCancellationReason(): ?int
    {
        return $this->cancellationReason;
    }

    /**
     * @return string|null
     */
    public function getPromotionalOfferId(): ?string
    {
        return $this->promotionalOfferId;
    }

    /**
     * @return string|null
     */
    public function getOfferCodeRefName(): ?string
    {
        return $this->offerCodeRefName;
    }

    /**
     * @return Cancellation|null
     * @psalm-suppress PossiblyNullArgument
     */
    public function getCancellation(): ?Cancellation
    {
        if (! is_null($this->cancellationDate) && ! is_null($this->cancellationReason)) {
            return new Cancellation($this->getCancellationDate(), $this->getCancellationReason());
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getAppAccountToken(): ?string
    {
        return $this->appAccountToken;
    }

    /**
     * @return string|null
     */
    public function getInAppOwnershipType(): ?string
    {
        return $this->inAppOwnershipType;
    }

    /**
     * @return bool|null
     */
    public function getIsInIntroOfferPeriod(): ?bool
    {
        if (is_string($this->isInIntroOfferPeriod)) {
            return strtolower($this->isInIntroOfferPeriod) === self::TRUE;
        }

        return $this->isInIntroOfferPeriod;
    }

    /**
     * @return bool|null
     */
    public function getIsTrialPeriod(): ?bool
    {
        if (is_string($this->isTrialPeriod)) {
            return strtolower($this->isTrialPeriod) === self::TRUE;
        }

        return $this->isTrialPeriod;
    }

    /**
     * @return bool|null
     */
    public function getIsUpgraded(): ?bool
    {
        if (is_string($this->isUpgraded)) {
            return strtolower($this->isUpgraded) === self::TRUE;
        }

        return $this->isUpgraded;
    }
}
