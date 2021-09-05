<?php

namespace Imdhemy\AppStore\Receipts;

use Imdhemy\AppStore\ValueObjects\LatestReceiptInfo;
use Imdhemy\AppStore\ValueObjects\PendingRenewal;
use Imdhemy\AppStore\ValueObjects\Receipt;
use Imdhemy\AppStore\ValueObjects\Status;

/**
 * Class ReceiptResponse
 * @package Imdhemy\AppStore\Receipts
 * @see https://developer.apple.com/documentation/appstorereceipts/responsebody
 */
class ReceiptResponse
{
    public const ENV_SANDBOX = 'Sandbox';
    public const ENV_PRODUCTION = 'Production';

    /**
     * The environment for which the receipt was generated.
     * @var string|null
     */
    protected $environment;

    /**
     * An indicator that an error occurred during the request.
     * @var bool|null
     */
    protected $isRetryable;

    /**
     * The latest Base64 encoded app receipt.
     * Only returned for receipts that contain auto-renewable subscriptions.
     * @var string|null
     */
    protected $latestReceipt;

    /**
     * An array that contains all in-app purchase transactions.
     * @var array|LatestReceiptInfo[]|null
     */
    protected $latestReceiptInfo;

    /**
     * In the JSON file, an array where each element contains the pending renewal information
     * for each auto-renewable subscription identified by the product_id.
     * @var array|PendingRenewal[]|null
     */
    protected $pendingRenewalInfo;

    /**
     * the receipt that was sent for verification.
     * @var array|null
     */
    protected $receipt;

    /**
     * Either 0 if the receipt is valid, or a status code if there is an error.
     * @see https://developer.apple.com/documentation/appstorereceipts/status
     * @var int
     */
    protected $status;

    /**
     * @var bool
     */
    private $parsedLatestReceiptInfo;

    /**
     * @var bool
     */
    private $parsedPendingRenewalInfo;

    /**
     * ReceiptResponse Constructor
     */
    public function __construct(int $status)
    {
        $this->status = $status;
        $this->parsedLatestReceiptInfo = false;
        $this->parsedPendingRenewalInfo = false;
    }

    /**
     * Static factory method
     * @param array $body
     * @return ReceiptResponse
     */
    public static function fromArray(array $body): self
    {
        $obj = new self($body['status']);

        $obj->environment = $body['environment'] ?? null;
        $obj->isRetryable = $body['is-retryable'] ?? null;
        $obj->latestReceipt = $body['latest_receipt'] ?? null;
        $obj->latestReceiptInfo = $body['latest_receipt_info'] ?? null;
        $obj->pendingRenewalInfo = $body['pending_renewal_info'] ?? null;
        $obj->receipt = $body['receipt'] ?? null;

        return $obj;
    }

    /**
     * @return string|null
     */
    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    /**
     * @return bool|null
     */
    public function getIsRetryable(): ?bool
    {
        return $this->isRetryable;
    }

    /**
     * @return string|null
     */
    public function getLatestReceipt(): ?string
    {
        return $this->latestReceipt;
    }

    /**
     * @return array|LatestReceiptInfo[]|null
     */
    public function getLatestReceiptInfo(): ?array
    {
        if (is_null($this->latestReceiptInfo)) {
            return null;
        }

        if ($this->parsedLatestReceiptInfo) {
            return $this->latestReceiptInfo;
        }

        $data = [];

        foreach ($this->latestReceiptInfo as $receiptInfo) {
            $data[] = LatestReceiptInfo::fromArray($receiptInfo);
        }

        $this->latestReceiptInfo = $data;
        $this->parsedLatestReceiptInfo = true;

        return $this->latestReceiptInfo;
    }

    /**
     * @return array|PendingRenewal[]|null
     */
    public function getPendingRenewalInfo(): ?array
    {
        if (is_null($this->pendingRenewalInfo)) {
            return null;
        }

        if ($this->parsedPendingRenewalInfo) {
            return $this->pendingRenewalInfo;
        }

        $data = [];
        foreach ($this->pendingRenewalInfo as $renewalInfo) {
            $data[] = PendingRenewal::fromArray($renewalInfo);
        }

        $this->pendingRenewalInfo = $data;
        $this->parsedPendingRenewalInfo = true;

        return $this->pendingRenewalInfo;
    }

    /**
     * @return Receipt|null
     */
    public function getReceipt(): ?Receipt
    {
        return
            is_array($this->receipt) ?
                Receipt::fromArray($this->receipt) :
                null;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return new Status($this->status);
    }
}
