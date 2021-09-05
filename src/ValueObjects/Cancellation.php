<?php

namespace Imdhemy\AppStore\ValueObjects;

/**
 * Class Cancellation
 * @package Imdhemy\AppStore\ValueObjects
 * Cancellation class represents two separate parts of information from the
 * response body receipt info, the cancellation time and reason.
 * The time and reason are received in two separate keys, but they are logically
 * related either coupled.
 * @see https://developer.apple.com/documentation/appstorereceipts/responsebody/latest_receipt_info
 */
final class Cancellation
{
    public const REASON_APP_ISSUE = 1;
    public const REASON_OTHER = 0;

    /**
     * The time the App Store refunded a transaction or revoked it from family sharing.
     * @var Time
     */
    private $time;

    /**
     * The reason for a refunded or revoked transaction.
     * A value of “1” indicates that the customer canceled their transaction
     * due to an actual or perceived issue within your app.
     *
     * A value of “0” indicates that the transaction was canceled for another reason;
     * for example, if the customer made the purchase accidentally.
     * @var int
     */
    private $reason;

    /**
     * Cancellation constructor.
     * @param Time $time
     * @param int $reason
     */
    public function __construct(Time $time, int $reason)
    {
        $this->time = $time;
        $this->reason = $reason;
    }

    /**
     * @return Time
     */
    public function getTime(): Time
    {
        return $this->time;
    }

    /**
     * @return int
     */
    public function getReason(): int
    {
        return $this->reason;
    }

    /**
     * @return bool
     */
    public function isDueAppIssue(): bool
    {
        return $this->reason === self::REASON_APP_ISSUE;
    }

    /**
     * @return bool
     */
    public function isDueAnotherReason(): bool
    {
        return $this->reason === self::REASON_OTHER;
    }
}
