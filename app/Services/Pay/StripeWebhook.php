<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Pay;

use App\Services\Pay\Stripe as StripePayService;
use App\Services\Service as AppService;
use Phalcon\Logger\Logger;
use Stripe\Checkout\Session;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhook extends AppService
{

    /**
     * @var string
     */
    protected string $env = 'live';

    /**
     * @var array
     */
    protected array $settings = [];

    /**
     * @var Logger
     */
    protected Logger $logger;

    public function __construct()
    {
        $this->initStripeSettings();
    }

    public function handle(): bool
    {
        $this->logger = $this->getLogger('stripe');

        $this->logger->debug('------ Webhook ------');

        $signature = $this->request->getHeader('Stripe-Signature');

        $this->logger->debug("Signature: {$signature}");

        $payload = $this->request->getRawBody();

        $this->logger->debug("Payload: {$payload}");

        $endpointSecret = $this->settings['webhook_secret'];

        try {

            $event = Webhook::constructEvent($payload, $signature, $endpointSecret);

            $this->logger->debug("Event Type: {$event->type}");

            $result = false;

            if ($event->type == 'checkout.session.completed') {
                $result = $this->handleCheckoutSessionCompletedEvent($event);
            }

            $this->logger->debug('Event Result: ' . ($result ? 'success' : 'failed'));

            return $result;

        } catch (SignatureVerificationException $e) {

            $this->logger->error('Signature Verification Exception: ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ]));
        }

        return false;
    }

    protected function handleCheckoutSessionCompletedEvent(Event $event): bool
    {
        /**
         * @var $session Session
         */
        $session = $event->data->object;

        $service = new StripePayService();

        $result = $service->completePurchase($session->id, $session->metadata['sn']);

        return $result['status'] != 'error';
    }

    protected function initStripeSettings(): void
    {
        $service = new StripePayService();

        $settings = $service->getStripeSettings();

        if ($settings['env'] == 'sandbox') {
            $this->env = 'sandbox';
        }

        $this->settings = $settings;
    }

}
