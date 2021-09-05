<?php

namespace Imdhemy\AppStore\ValueObjects;

/**
 * Status class
 * The status of the app receipt.
 * @see https://developer.apple.com/documentation/appstorereceipts/status?changes=latest_minor
 */
final class Status
{
    public const STATUS_VALID = 0;
    public const STATUS_SUBSCRIPTION_EXPIRED = 21006;

    public const STATUS_REQUEST_METHOD_NOT_POST = 21000;
    public const STATUS_STATUS_CODE_NOT_SENT = 21001;
    public const STATUS_RECEIPT_DATA_MALFORMED = 21002;
    public const STATUS_COULD_NOT_BE_AUTHENTICATED = 21003;
    public const STATUS_INVALID_SHARED_SECRET = 21004;
    public const STATUS_SERVER_UNAVAILABLE = 21005;
    public const STATUS_FROM_TEST_ENV = 21007;
    public const STATUS_FROM_PRODUCTION_ENV = 21008;
    public const STATUS_INTERNAL_DATA_ACCESS_ERROR_21009 = 21009;
    public const STATUS_ACCOUNT_NOT_FOUND = 21010;
    public const STATUS_INTERNAL_DATA_ACCESS_ERROR_21100 = 21100;
    public const STATUS_INTERNAL_DATA_ACCESS_ERROR_21199 = 21199;

    public const ERROR_STATUS_MAP = [
        self::STATUS_REQUEST_METHOD_NOT_POST => 'The request to the App Store was not made using the HTTP POST request method.',
        self::STATUS_STATUS_CODE_NOT_SENT => 'This status code is no longer sent by the App Store.',
        self::STATUS_RECEIPT_DATA_MALFORMED => 'The data in the receipt-data property was malformed or the service experienced a temporary issue. Try again.',
        self::STATUS_COULD_NOT_BE_AUTHENTICATED => 'The receipt could not be authenticated.',
        self::STATUS_INVALID_SHARED_SECRET => 'The shared secret you provided does not match the shared secret on file for your account.',
        self::STATUS_SERVER_UNAVAILABLE => 'The receipt server was temporarily unable to provide the receipt. Try again.',
        self::STATUS_FROM_TEST_ENV => 'This receipt is from the test environment, but it was sent to the production environment for verification.',
        self::STATUS_FROM_PRODUCTION_ENV => 'This receipt is from the production environment, but it was sent to the test environment for verification.',
        self::STATUS_INTERNAL_DATA_ACCESS_ERROR_21009 => 'Internal data access error. Try again later.',
        self::STATUS_ACCOUNT_NOT_FOUND => 'The user account cannot be found or has been deleted.',
        self::STATUS_INTERNAL_DATA_ACCESS_ERROR_21100 => 'Internal data access error.',
        self::STATUS_INTERNAL_DATA_ACCESS_ERROR_21199 => 'Internal data access error.',
    ];

    /**
     * @var int
     */
    private $value;

    /**
     * Status constructor.
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return
            $this->value === self::STATUS_VALID ||
            $this->value === self::STATUS_SUBSCRIPTION_EXPIRED;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
