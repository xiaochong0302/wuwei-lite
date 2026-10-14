<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Services\Pay;

use App\Services\Pay\Paypal as PaypalPayService;
use App\Services\Service as AppService;
use Phalcon\Logger\Logger;

class PaypalWebhook extends AppService
{

    /**
     * @var string
     */
    protected string $env = 'live';

    /**
     * @var array
     */
    protected array $paypalSettings;

    /**
     * @var Logger
     */
    protected Logger $paypalLogger;

    public function __construct()
    {
        $this->paypalSettings = $this->getPaypalSettings();

        if ($this->paypalSettings['env'] == 'sandbox') {
            $this->env = 'sandbox';
        }

        $this->paypalLogger = $this->getLogger('paypal');
    }

    public function handle(): bool
    {
        $this->paypalLogger->debug('------ Webhook ------');

        $headers = $this->request->getHeaders();

        $this->paypalLogger->debug('Headers: ' . kg_json_encode($headers));

        $payload = $this->request->getRawBody();

        $this->paypalLogger->debug('Payload: ' . $payload);

        $webhookId = $this->paypalSettings['webhook_id'];

        $isValidSignature = $this->isValidSignature($headers, $payload, $webhookId);

        if (!$isValidSignature) return false;

        return $this->handleEvent($payload);
    }

    protected function handleEvent(string $payload): bool
    {
        $payload = json_decode($payload, true);

        $eventType = $payload['event_type'] ?? '';

        $this->paypalLogger->debug('Event Type: ' . $eventType);

        $result = false;

        if ($eventType == 'CHECKOUT.ORDER.APPROVED') {

            $paypalOrderId = $payload['resource']['id'] ?? '';
            $localTradeSn = $payload['resource']['purchase_units'][0]['custom_id'] ?? '';

            $this->paypalLogger->debug("paypalOrderId: {$paypalOrderId}, localTradeSn: {$localTradeSn}");

            $service = new PaypalPayService();

            $response = $service->completePurchase($paypalOrderId, $localTradeSn);

            $result = $response['status'] != 'error';
        }

        $this->paypalLogger->debug('Event Result: ' . ($result ? 'success' : 'failed'));

        return $result;
    }

    protected function isValidSignature(array $headers, string $payload, string $webhookId): bool
    {
        $transmissionSig = base64_decode($headers['Paypal-Transmission-Sig']);
        $transmissionId = $headers['Paypal-Transmission-Id'];
        $transmissionTime = $headers['Paypal-Transmission-Time'];
        $certUrl = $headers['Paypal-Cert-Url'];

        $sigInput = $transmissionId . '|' . $transmissionTime . '|' . $webhookId . '|' . crc32($payload);

        $this->paypalLogger->debug('sign input: ' . $sigInput);

        $cert = $this->getCert($certUrl);

        $publicKey = openssl_get_publickey($cert);

        $result = openssl_verify($sigInput, $transmissionSig, $publicKey, OPENSSL_ALGO_SHA256) == 1;

        if (!$result) {
            $this->paypalLogger->debug('sign verify failed');
        }

        return $result;
    }

    protected function getCert(string $certUrl): string
    {
        $cacheKey = "paypal-webhook-cert-{$this->env}";

        $cache = $this->getCache();

        $content = $cache->get($cacheKey);

        if ($content) return $content;

        $content = file_get_contents($certUrl);

        $cache->set($cacheKey, $content, 86400);

        return $content;
    }

    protected function getPaypalSettings(): array
    {
        $service = new PaypalPayService();

        return $service->getPaypalSettings();
    }

}
