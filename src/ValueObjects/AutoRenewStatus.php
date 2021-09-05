<?php

namespace Imdhemy\AppStore\ValueObjects;

/**
 * AutoRenewStatus class
 * The renewal status for the auto-renewable subscription.
 * @see https://developer.apple.com/documentation/appstorereceipts/auto_renew_status?changes=latest_minor
 */
final class AutoRenewStatus
{
    public const WILL_RENEW = 1;
    public const TURNED_OFF = 0;

    /**
     * @var int
     */
    private $value;

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getValue();
    }
}
