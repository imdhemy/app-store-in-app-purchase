<?php

namespace Imdhemy\AppStore\Receipts;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;

/**
 * Verifier class
 * @package Imdhemy\AppStore;
 * This class is responsible for handling verification requests
 * @see https://developer.apple.com/documentation/appstorereceipts/verifyreceipt
 */
class Verifier
{
    public const TEST_ENV_CODE = 21007;

    public const VERIFY_RECEIPT_PATH = '/verifyReceipt';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $receiptData;

    /**
     * @var string
     */
    protected $password;

    /**
     * Receipt constructor.
     * @param Client $client
     * @param string $receiptData
     * @param string $password
     */
    public function __construct(Client $client, string $receiptData, string $password)
    {
        $this->client = $client;
        $this->receiptData = $receiptData;
        $this->password = $password;
    }

    /**
     * @param Client|null $sandboxClient
     * @return ReceiptResponse
     * @throws GuzzleException|InvalidReceiptException
     */
    public function verifyRenewable(?Client $sandboxClient = null): ReceiptResponse
    {
        return $this->verify(true, $sandboxClient);
    }

    /**
     * @param bool $excludeOldTransactions
     * @param Client|null $sandboxClient
     * @return ReceiptResponse
     * @throws GuzzleException|InvalidReceiptException
     */
    public function verify(bool $excludeOldTransactions = false, ?Client $sandboxClient = null): ReceiptResponse
    {
        $responseBody = $this->sendVerifyRequest($excludeOldTransactions);
        $status = $responseBody['status'];

        if ($this->isInvalidReceiptStatus($status)) {
            throw InvalidReceiptException::create($status);
        }

        if ($this->isFromTestEnv($status)) {
            $sandboxClient = $sandboxClient ?? ClientFactory::createSandbox();
            $responseBody = $this->sendVerifyRequest($excludeOldTransactions, $sandboxClient);
        }

        return ReceiptResponse::fromArray($responseBody);
    }

    /**
     * @param bool $excludeOldTransactions
     * @param Client|null $client
     * @return array
     * @throws GuzzleException
     */
    private function sendVerifyRequest(bool $excludeOldTransactions = false, ?Client $client = null): array
    {
        $client = $client ?? $this->client;
        $options = $this->buildRequestOptions($excludeOldTransactions);
        $response = $client->post(self::VERIFY_RECEIPT_PATH, $options);

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * @param bool $excludeOldTransactions
     * @return array[]
     */
    private function buildRequestOptions(bool $excludeOldTransactions): array
    {
        return [
            'json' => [
                'receipt-data' => $this->receiptData,
                'password' => $this->password,
                'exclude-old-transactions' => $excludeOldTransactions,
            ],
        ];
    }

    /**
     * @param int $status
     * @return bool
     */
    private function isInvalidReceiptStatus(int $status): bool
    {
        if ($status === self::TEST_ENV_CODE) {
            return false;
        }

        return in_array($status, array_keys(InvalidReceiptException::ERROR_STATUS_MAP));
    }

    /**
     * @param int $status
     * @return bool
     */
    private function isFromTestEnv(int $status): bool
    {
        return $status === self::TEST_ENV_CODE;
    }
}
