<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Pay;

use App\Models\KgSale as KgSaleModel;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Repos\Order as OrderRepo;
use App\Services\Service as AppService;
use Phalcon\Logger\Logger;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\StripeClient;

class Stripe extends AppService
{

    /**
     * @var StripeClient
     */
    protected StripeClient $client;

    /**
     * @var Logger
     */
    protected Logger $logger;

    public function __construct()
    {
        $this->client = $this->getClient();

        $this->logger = $this->getLogger('stripe');
    }

    public function purchase(OrderModel $order): array
    {
        $this->logger->debug('------ Purchase ------');

        $successUrl = kg_full_url(
                ['for' => 'home.payment.stripe_success'],
                ['sn' => $order->sn],
            ) . '&session_id={CHECKOUT_SESSION_ID}';

        $cancelUrl = kg_full_url(
            ['for' => 'home.payment.stripe_cancel'],
            ['sn' => $order->sn],
        );

        /**
         * 注意：货币是小写的
         */
        $currency = strtolower($order->currency);

        /**
         * 注意：金额以分为单位
         */
        $unitAmount = $this->getUnitAmount($order->currency, $order->amount);

        try {

            $params = [
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => $currency,
                            'unit_amount' => $unitAmount,
                            'tax_behavior' => 'inclusive',
                            'product_data' => [
                                'name' => $order->subject,
                            ],
                        ],
                        'quantity' => 1,
                    ],
                ],
                'metadata' => [
                    'sn' => $order->sn,
                ],
            ];

            $this->logger->debug('Create Session Params: ' . kg_json_encode($params));

            $session = $this->client->checkout->sessions->create($params);

            $this->logger->debug('Create Session Response: ' . kg_json_encode($session));

            $result = [
                'status' => 'redirect',
                'message' => '',
                'redirect_url' => '',
            ];

            if ($session->payment_status == Session::PAYMENT_STATUS_UNPAID) {

                $paymentInfo = $order->_stripe_info;
                $paymentInfo['session_id'] = $session->id;
                $order->payment_type = OrderModel::PAYMENT_STRIPE;
                $order->payment_info = $paymentInfo;
                $order->update();

                $result = [
                    'status' => 'redirect',
                    'message' => 'purchase ok',
                    'redirect_url' => $session->url,
                ];
            }

            return $result;

        } catch (ApiErrorException $e) {

            $this->logger->error('Create Session Exception: ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ]));

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function completePurchase(string $sessionId, string $sn): array
    {
        $this->logger->debug('------ Complete Purchase ------');

        $orderRepo = new orderRepo();

        $order = $orderRepo->findBySn($sn);

        /**
         * 参数不一致，可能是伪造的请求
         */
        if ($sessionId != $order->payment_info['session_id']) {
            return [
                'status' => 'skipped',
                'message' => 'session not match',
            ];
        }

        /**
         * 防止二次执行以下操作
         */
        if (!empty($order->payment_info['payment_intent_id'])) {
            return [
                'status' => 'skipped',
                'message' => 'duplicated action',
            ];
        }

        $result = [
            'status' => 'pending',
            'message' => 'wait proceed',
        ];

        $session = $this->retrieveCheckoutSession($sessionId);

        if ($session->payment_status == Session::PAYMENT_STATUS_PAID) {

            $paymentInfo = $order->payment_info;
            $paymentInfo['payment_intent_id'] = $session->payment_intent;
            $order->payment_info = $paymentInfo;
            $order->status = OrderModel::STATUS_PAID;
            $order->update();

            $this->logger->debug('trigger Order:afterPay event');

            $this->eventsManager->fire('Order:afterPay', $this, $order);

            $result = [
                'status' => 'success',
                'message' => 'payment ok',
            ];
        }

        return $result;
    }

    public function cancelPurchase(string $sn): void
    {
        $this->logger->debug('------ Cancel Purchase ------');

        $this->logger->debug("sn:{$sn}");
    }

    public function refund(RefundModel $refund): array
    {
        $this->logger->debug('------ Create Refund ------');

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($refund->order_id);

        /**
         * 防止二次发送退款请求
         */
        if (!empty($order->payment_info['refund_id'])) {
            return [
                'status' => 'skipped',
                'message' => 'duplicated action',
            ];
        }

        try {

            $params = [
                'payment_intent' => $order->payment_info['payment_intent_id'],
                'amount' => $this->getUnitAmount($refund->currency, $refund->amount),
                'metadata' => ['sn' => $refund->sn],
            ];

            $this->logger->debug('Refund Params: ' . kg_json_encode($params));

            $stripeRefund = $this->client->refunds->create($params);

            $this->logger->debug('Refund Response: ' . kg_json_encode($stripeRefund));

            $result = [
                'status' => 'pending',
                'message' => 'wait proceed',
            ];

            if ($stripeRefund->status == Refund::STATUS_SUCCEEDED) {

                $paymentInfo = $order->payment_info;
                $paymentInfo['refund_id'] = $stripeRefund->id;
                $order->payment_info = $paymentInfo;
                $order->update();

                $result = [
                    'status' => 'success',
                    'message' => 'refund ok',
                ];
            }

            return $result;

        } catch (ApiErrorException$e) {

            $this->logger->error('Refund Exception: ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function retrieveCheckoutSession(string $id): ?Session
    {
        try {

            return $this->client->checkout->sessions->retrieve($id);

        } catch (ApiErrorException $e) {

            $this->logger->error('Retrieve Checkout Session Exception: ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ]));
        }

        return null;
    }

    public function retrievePaymentIndent(string $id): ?PaymentIntent
    {
        $this->logger->debug('------ Retrieve Payment Indent ------');

        try {

            return $this->client->paymentIntents->retrieve($id);

        } catch (ApiErrorException $e) {

            $this->logger->error('Retrieve Payment Indent Exception: ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));
        }

        return null;
    }

    public function retrieveRefund(string $id): ?Refund
    {
        $this->logger->debug('------ Retrieve Refund ------');

        try {

            return $this->client->refunds->retrieve($id);

        } catch (ApiErrorException $e) {

            $this->logger->error('Retrieve Refund Exception: ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));
        }

        return null;
    }

    public function getStripeSettings(): array
    {
        $settings = $this->getSettings('payment.stripe');

        $config = $this->getConfig();

        if ($config->path('payment.stripe.env') == 'sandbox') {
            $settings = $config->path('payment.stripe')->toArray();
        }

        return $settings;
    }

    protected function getUnitAmount(string $currency, float $amount): int
    {
        $items = KgSaleModel::currencies();

        $unit = $items[$currency]['unit'] ?? 100;

        return intval($amount * $unit);
    }

    protected function getClient(): StripeClient
    {
        $settings = $this->getStripeSettings();

        return new StripeClient($settings['api_key']);
    }

}
