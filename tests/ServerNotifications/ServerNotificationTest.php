<?php

namespace Imdhemy\AppStore\Tests\ServerNotifications;

use Exception;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use PHPUnit\Framework\TestCase;

class ServerNotificationTest extends TestCase
{
    /**
     * @test
     */
    public function test_it_can_be_constructed_from_array()
    {
        $path = realpath(__DIR__ . '/../fixtures/server-notification.json');
        $serverNotificationBody = json_decode(file_get_contents($path), true);

        $serverNotification = ServerNotification::fromArray($serverNotificationBody);
        $this->assertInstanceOf(ServerNotification::class, $serverNotification);
    }

    /**
     * @test
     */
    public function test_unified_receipt()
    {
        $value = ['status' => 0];
        $serverNotification = ServerNotification::fromArray(
            [
                'unified_receipt' => $value,
                'notification_type' => ServerNotification::CANCEL,
            ]
        );

        $this->assertEquals(0, $serverNotification->getUnifiedReceipt()->getStatus()->getValue());

        $missingData = ServerNotification::fromArray(['notification_type' => ServerNotification::CONSUMPTION_REQUEST]);
        $this->assertNull($missingData->getUnifiedReceipt());
    }

    /**
     * @test
     * @throws Exception
     */
    public function test_auto_renew_status_change_date()
    {
        $value = "1606293002000";

        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'auto_renew_status_change_date_ms' => $value,
            ]
        );

        $this->assertEquals(
            $value,
            $serverNotification->getAutoRenewStatusChangeDate()->getCarbon()->getTimestampMs()
        );

        $missingData = ServerNotification::fromArray(
            ['notification_type' => ServerNotification::DID_CHANGE_RENEWAL_PREF]
        );

        $this->assertNull($missingData->getAutoRenewStatusChangeDate());
    }

    /**
     * @test
     */
    public function test_environment()
    {
        $value = ReceiptResponse::ENV_SANDBOX;
        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'environment' => $value,
            ]
        );
        $this->assertEquals($value, $serverNotification->getEnvironment());

        $missingData = ServerNotification::fromArray(
            ['notification_type' => ServerNotification::DID_CHANGE_RENEWAL_STATUS]
        );
        $this->assertNull($missingData->getEnvironment());
    }

    /**
     * @test
     */
    public function test_auto_renew_status()
    {
        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'auto_renew_status' => "true",
            ]
        );
        $this->assertTrue($serverNotification->getAutoRenewStatus());

        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'auto_renew_status' => true,
            ]
        );
        $this->assertTrue($serverNotification->getAutoRenewStatus());

        $missingData = ServerNotification::fromArray(['notification_type' => ServerNotification::DID_FAIL_TO_RENEW]);
        $this->assertNull($missingData->getAutoRenewStatus());
    }

    /**
     * @test
     */
    public function test_bvrs()
    {
        $value = "33";
        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'bvrs' => $value,
            ]
        );
        $this->assertEquals($value, $serverNotification->getBvrs());

        $missingData = ServerNotification::fromArray(['notification_type' => ServerNotification::DID_RECOVER]);
        $this->assertNull($missingData->getBvrs());
    }

    /**
     * @test
     */
    public function test_bid()
    {
        $value = "com.some.thing";
        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'bid' => $value,
            ]
        );
        $this->assertEquals($value, $serverNotification->getBid());

        $missingData = ServerNotification::fromArray(['notification_type' => ServerNotification::DID_RENEW]);
        $this->assertNull($missingData->getBid());
    }

    /**
     * @test
     */
    public function test_password()
    {
        $value = "******";
        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'password' => $value,
            ]
        );
        $this->assertEquals($value, $serverNotification->getPassword());

        $missingData = ServerNotification::fromArray(['notification_type' => ServerNotification::INITIAL_BUY]);
        $this->assertNull($missingData->getPassword());
    }

    /**
     * @test
     */
    public function test_auto_renew_production_id()
    {
        $value = "fake_product_id";
        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'auto_renew_product_id' => $value,
            ]
        );
        $this->assertEquals($value, $serverNotification->getAutoRenewProductId());

        $missingData = ServerNotification::fromArray(['notification_type' => ServerNotification::INTERACTIVE_RENEWAL]);
        $this->assertNull($missingData->getAutoRenewProductId());
    }

    /**
     * @test
     */
    public function test_notification_type()
    {
        $value = ServerNotification::PRICE_INCREASE_CONSENT;
        $serverNotification = ServerNotification::fromArray(
            ['notification_type' => $value]
        );
        $this->assertEquals($value, $serverNotification->getNotificationType());
    }
}
