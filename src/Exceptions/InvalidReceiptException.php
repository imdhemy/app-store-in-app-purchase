<?php

namespace Imdhemy\AppStore\Exceptions;

use Exception;
use Imdhemy\AppStore\ValueObjects\Status;

/**
 * Class InvalidReceiptException
 * @package Imdhemy\AppStore\Exceptions
 */
class InvalidReceiptException extends Exception
{
    public const ERROR_STATUS_MAP = Status::ERROR_STATUS_MAP;

    /**
     * @param int $status
     * @return InvalidReceiptException
     */
    public static function create(int $status): self
    {
        $msg = self::ERROR_STATUS_MAP[$status];

        return new self($msg, $status);
    }
}
