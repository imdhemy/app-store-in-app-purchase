<?php

namespace Imdhemy\AppStore\Tests\ValueObjects;

use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\Status;

class StatusTest extends TestCase
{
    /**
     * @test
     */
    public function test_basic_usage()
    {
        $values = range(
            Status::STATUS_REQUEST_METHOD_NOT_POST,
            Status::STATUS_ACCOUNT_NOT_FOUND
        );

        $value = $this->faker->randomElement($values);
        $status = new Status($value);
        $this->assertSame($value, $status->getValue());

        if ($value === Status::STATUS_SUBSCRIPTION_EXPIRED) {
            $this->assertTrue($status->isValid());
        } else {
            $this->assertFalse($status->isValid());
        }
    }

    /**
     * @test
     */
    public function test_valid_status()
    {
        $value = Status::STATUS_VALID;
        $status = new Status($value);
        $this->assertSame($value, $status->getValue());
        $this->assertTrue($status->isValid());
    }

    /**
     * @test
     */
    public function test_expired_status_is_valid()
    {
        $value = Status::STATUS_SUBSCRIPTION_EXPIRED;
        $status = new Status($value);
        $this->assertSame($value, $status->getValue());
        $this->assertTrue($status->isValid());
    }
}
