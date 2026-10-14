<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
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
    protected array $stripeSettings;

    /**
     * @var Logger
     */
    protected Logger $stripeLogger;

    public function __construct()
    {
        $this->stripeSettings = $this->getStripeSettings();

        if ($this->stripeSettings['env'] == 'sandbox') {
            $this->env = 'sandbox';
        }

        $this->stripeLogger = $this->getLogger('stripe');
    }

    public function handle(): bool
    {
        $this->stripeLogger->debug('------ Webhook ------');

        $signature = $this->request->getHeader('Stripe-Signature');

        $this->stripeLogger->debug("Signature: {$signature}");

        $payload = $this->request->getRawBody();

        $this->stripeLogger->debug("Payload: {$payload}");

        $endpointSecret = $this->stripeSettings['webhook_secret'];

        try {

            $event = Webhook::constructEvent($payload, $signature, $endpointSecret);

            $this->stripeLogger->debug("Event Type: {$event->type}");

            $result = false;

            if ($event->type == 'checkout.session.completed') {
                $result = $this->handleCheckoutSessionCompletedEvent($event);
            }

            $this->stripeLogger->debug('Event Result: ' . ($result ? 'success' : 'failed'));

            return $result;

        } catch (SignatureVerificationException $e) {

            $this->stripeLogger->error('Signature Verification Exception: ' . kg_json_encode([
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

    protected function getStripeSettings(): array
    {
        $service = new StripePayService();

        return $service->getStripeSettings();
    }

}
