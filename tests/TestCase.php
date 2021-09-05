<?php

namespace Imdhemy\AppStore\Tests;

use Faker\Factory;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $faker;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    /**
     * @return string
     */
    protected function getSubscriptionReceipt(): string
    {
        return file_get_contents(__DIR__ . '/fixtures/subscription_receipt.json');
    }


    /**
     * @param array $override
     * @return string
     */
    protected function getVerifyReceiptResponse(array $override = []): string
    {
        $contents = file_get_contents(__DIR__ . '/fixtures/verify_receipt_response.json');
        $data = json_decode($contents, true);
        $response = array_merge($data, $override);

        return json_encode($response);
    }
}
