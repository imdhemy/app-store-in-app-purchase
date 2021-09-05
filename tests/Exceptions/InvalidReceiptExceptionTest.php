<?php

namespace Imdhemy\AppStore\Tests\Exceptions;

use Imdhemy\AppStore\Exceptions\InvalidReceiptException;
use PHPUnit\Framework\TestCase;

class InvalidReceiptExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function test_create()
    {
        $errorStatusMap = InvalidReceiptException::ERROR_STATUS_MAP;
        $statusList = array_keys($errorStatusMap);

        $status = $statusList[array_rand($statusList)];
        $message = $errorStatusMap[$status];

        $exception = InvalidReceiptException::create($status);

        $this->assertEquals($message, $exception->getMessage());
    }
}
