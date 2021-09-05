<?php

namespace Imdhemy\AppStore\Tests\ValueObjects;

use Carbon\Carbon;
use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\Time;

class TimeTest extends TestCase
{
    /**
     * @test
     */
    public function test_is_future()
    {
        $dateTime = $this->faker->dateTimeBetween('+1 year', '+2 years');
        $value = $dateTime->getTimestamp() * 1000;
        $time = new Time($value);
        $this->assertTrue($time->isFuture());
    }

    /**
     * @test
     */
    public function test_is_past()
    {
        $dateTime = $this->faker->dateTimeBetween('-30 years', '-1 year');
        $value = $dateTime->getTimestamp() * 1000;
        $time = new Time($value);
        $this->assertTrue($time->isPast());
    }

    /**
     * @test
     */
    public function test_get_carbon()
    {
        $dateTime = $this->faker->dateTimeBetween('-30 years', '-1 year');
        $value = $dateTime->getTimestamp() * 1000;
        $carbon = Carbon::createFromTimestampMs($value);
        $time = new Time($value);
        $this->assertEquals($carbon, $time->getCarbon());
        $this->assertEquals($carbon, $time->toCarbon());
    }

    /**
     * @test
     */
    public function test_to_date_time()
    {
        $dateTime = $this->faker->dateTimeBetween('-30 years', '-1 year');
        $value = $dateTime->getTimestamp() * 1000;
        $time = new Time($value);
        $this->assertEquals($dateTime, $time->toDateTime());
    }
}
