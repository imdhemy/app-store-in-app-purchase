<?php

namespace Imdhemy\AppStore\Tests\ValueObjects;

use Exception;
use Imdhemy\AppStore\ValueObjects\AutoRenewStatus;
use PHPUnit\Framework\TestCase;

class AutoRenewStatusTest extends TestCase
{
    /**
     * @test
     * @throws Exception
     */
    public function test_basic_usage()
    {
        $value = [AutoRenewStatus::TURNED_OFF, AutoRenewStatus::WILL_RENEW][random_int(0, 1)];
        $status = new AutoRenewStatus($value);
        $this->assertEquals($value, $status->getValue());
    }
}
