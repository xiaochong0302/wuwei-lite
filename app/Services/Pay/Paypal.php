<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Pay;

use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Repos\Order as OrderRepo;
use App\Services\Service as AppService;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\Exceptions\ApiException;
use PaypalServerSdkLib\Models\Builders\AmountBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\AmountWithBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\ItemBuilder;
use PaypalServerSdkLib\Models\Builders\MoneyBuilder;
use PaypalServerSdkLib\Models\Builders\OrderApplicationContextBuilder;
use PaypalServerSdkLib\Models\Builders\OrderRequestBuilder;
use PaypalServerSdkLib\Models\Builders\PurchaseUnitRequestBuilder;
use PaypalServerSdkLib\Models\Builders\RefundRequestBuilder;
use PaypalServerSdkLib\Models\CapturedPayment;
use PaypalServerSdkLib\Models\CaptureStatus;
use PaypalServerSdkLib\Models\CheckoutPaymentIntent;
use PaypalServerSdkLib\Models\Order;
use PaypalServerSdkLib\Models\OrderStatus;
use PaypalServerSdkLib\Models\Refund;
use PaypalServerSdkLib\Models\RefundStatus;
use PaypalServerSdkLib\PaypalServerSdkClient;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use Phalcon\Logger\Logger;

class Paypal extends AppService
{

    /**
     * @var PaypalServerSdkClient
     */
    protected PaypalServerSdkClient $client;

    /**
     * @var Logger
     */
    protected Logger $logger;

    public function __construct()
    {
        $this->client = $this->getClient();

        $this->logger = $this->getLogger('paypal');
    }

    public function purchase(OrderModel $order): array
    {
        $this->logger->debug('------ Purchase ------');

        $result = [
            'status' => 'redirect',
            'message' => '',
            'redirect_url' => '',
        ];

        try {

            $returnUrl = kg_full_url(
                ['for' => 'home.payment.paypal_success'],
                ['sn' => $order->sn],
            );

            $cancelUrl = kg_full_url(
                ['for' => 'home.payment.paypal_cancel'],
                ['sn' => $order->sn],
            );

            $applicationContext = OrderApplicationContextBuilder::init()
                ->returnUrl($returnUrl)
                ->cancelUrl($cancelUrl)
                ->build();

            $purchaseUnits = [];

            $items = [];

            $amount = MoneyBuilder::init($order->currency, $order->amount)->build();

            $items[] = ItemBuilder::init($order->subject, $amount, 1)->build();

            $itemTotal = MoneyBuilder::init($order->currency, $order->amount)->build();

            $breakdown = AmountBreakdownBuilder::init()->itemTotal($itemTotal)->build();

            $amountWithBreakdown = AmountWithBreakdownBuilder::init($order->currency, $order->amount)
                ->breakdown($breakdown)
                ->build();

            $purchaseUnits[] = PurchaseUnitRequestBuilder::init($amountWithBreakdown)
                ->items($items)
                ->customId($order->sn)
                ->build();

            $body = OrderRequestBuilder::init(CheckoutPaymentIntent::CAPTURE, $purchaseUnits)
                ->applicationContext($applicationContext)
                ->build();

            $collect = ['body' => $body];

            $this->logger->debug('Create Order Params: ' . kg_json_encode($collect));

            $response = $this->client->getOrdersController()->createOrder($collect);

            /**
             * @var $paypalOrder Order
             */
            $paypalOrder = $response->getResult();

            $this->logger->debug('Create Order Response: ' . kg_json_encode($paypalOrder));

            if ($paypalOrder->getStatus() == OrderStatus::CREATED) {

                $paymentInfo = $order->_paypal_info;
                $paymentInfo['order_id'] = $paypalOrder->getId();
                $order->payment_type = OrderModel::PAYMENT_PAYPAL;
                $order->payment_info = $paymentInfo;
                $order->update();

                foreach ($paypalOrder->getLinks() as $link) {
                    if ($link->getRel() == 'approve') {
                        $result = [
                            'status' => 'redirect',
                            'message' => 'create order ok',
                            'redirect_url' => $link->getHref(),
                        ];
                    }
                }
            }

            return $result;

        } catch (ApiException $e) {

            $this->logger->error('Create Order Exception: ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ]));

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function completePurchase(string $token, string $sn): array
    {
        $this->logger->debug('------ Complete Purchase ------');

        $this->logger->debug("token:{$token}, sn:{$sn}");

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findBySn($sn);

        /**
         * 参数不一致，可能是伪造的请求
         */
        if ($token != $order->payment_info['order_id']) {
            return [
                'status' => 'skipped',
                'message' => 'token not match',
            ];
        }

        /**
         * 防止二次执行 captureOrder
         */
        if (!empty($order->payment_info['capture_id'])) {
            return [
                'status' => 'skipped',
                'message' => 'duplicated action',
            ];
        }

        try {

            $collect = ['id' => $token];

            $this->logger->debug('Capture Order Params: ' . kg_json_encode($collect));

            $response = $this->client->getOrdersController()->captureOrder($collect);

            /**
             * @var $paypalOrder Order
             */
            $paypalOrder = $response->getResult();

            $this->logger->debug('Capture Order Response: ' . kg_json_encode($paypalOrder));

            $captureId = $paypalOrder->getPurchaseUnits()[0]->getPayments()->getCaptures()[0]->getId();
            $captureStatus = $paypalOrder->getPurchaseUnits()[0]->getPayments()->getCaptures()[0]->getStatus();

            $result = [
                'status' => 'pending',
                'message' => 'wait process',
            ];

            if ($captureStatus == CaptureStatus::COMPLETED) {

                $paymentInfo = $order->payment_info;
                $paymentInfo['capture_id'] = $captureId;
                $order->payment_info = $paymentInfo;
                $order->status = OrderModel::STATUS_PAID;
                $order->update();

                $this->logger->debug('trigger Order:afterPay event');

                $this->eventsManager->fire('Order:afterPay', $this, $order);

                $result = [
                    'status' => 'success',
                    'message' => 'Capture Order OK',
                ];
            }

            return $result;

        } catch (ApiException $e) {

            $this->logger->error('Capture Order Exception: ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ]));

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function cancelPurchase(string $token, string $sn): void
    {
        $this->logger->debug('------ Cancel Purchase ------');

        $this->logger->debug("token:{$token}, sn:{$sn}");
    }

    public function refund(RefundModel $refund): array
    {
        $this->logger->debug('------ Refund ------');

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

            $amount = MoneyBuilder::init($refund->currency, $refund->amount)->build();

            $body = RefundRequestBuilder::init()->amount($amount)->build();

            $collect = [
                'captureId' => $order->payment_info['capture_id'],
                'body' => $body,
            ];

            $this->logger->debug('Refund Params: ' . kg_json_encode($collect));

            $response = $this->client->getPaymentsController()->refundCapturedPayment($collect);

            /**
             * @var $paypalRefund Refund
             */
            $paypalRefund = $response->getResult();

            $this->logger->debug('Refund Response: ' . kg_json_encode($paypalRefund));

            $result = [
                'status' => 'pending',
                'message' => 'wait process',
            ];

            if ($paypalRefund->getStatus() == RefundStatus::COMPLETED) {

                $paymentInfo = $order->payment_info;
                $paymentInfo['refund_id'] = $paypalRefund->getId();
                $order->payment_info = $paymentInfo;
                $order->update();

                $result = [
                    'status' => 'success',
                    'message' => 'refund ok',
                ];
            }

            return $result;

        } catch (ApiException $e) {

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

    public function getCapturedPayment(string $id): ?CapturedPayment
    {
        $this->logger->debug('------ step: Get Captured Payment ------');

        try {

            $collect = ['captureId' => $id];

            $response = $this->client->getPaymentsController()->getCapturedPayment($collect);

            /**
             * @var $payment CapturedPayment
             */
            $payment = $response->getResult();

            $this->logger->debug('Get Payment Response: ' . kg_json_encode($payment));

            return $payment;

        } catch (ApiException $e) {

            $this->logger->error('Get Payment Exception: ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));
        }

        return null;
    }

    public function getOrder(string $id): ?Order
    {
        $this->logger->debug('------ Get Order ------');

        try {

            $collect = ['id' => $id];

            $response = $this->client->getOrdersController()->getOrder($collect);

            /**
             * @var $order Order
             */
            $order = $response->getResult();

            $this->logger->debug('Get Order Response: ' . kg_json_encode($order));

            return $order;

        } catch (ApiException $e) {

            $this->logger->error('Get Order Exception: ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));
        }

        return null;
    }

    public function getRefund(string $id): ?Refund
    {
        $this->logger->debug('------ Get Refund ------');

        try {

            $collect = ['refundId' => $id];

            $response = $this->client->getPaymentsController()->getRefund($collect);

            /**
             * @var $refund Refund
             */
            $refund = $response->getResult();

            $this->logger->debug('Get Refund Response: ' . kg_json_encode($refund));

            return $refund;

        } catch (ApiException $e) {

            $this->logger->error('Get Refund Exception: ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));
        }

        return null;
    }

    public function getPaypalSettings(): array
    {
        $settings = $this->getSettings('payment.paypal');

        $config = $this->getConfig();

        if ($config->path('payment.paypal.env') == 'sandbox') {
            $settings = $config->path('payment.paypal')->toArray();
        }

        return $settings;
    }

    protected function getClient(): PaypalServerSdkClient
    {
        $payEnv = Environment::PRODUCTION;

        $settings = $this->getPaypalSettings();

        if ($settings['env'] == 'sandbox') {
            $payEnv = Environment::SANDBOX;
        }

        $clientCredentialsAuth = ClientCredentialsAuthCredentialsBuilder::init(
            $settings['client_id'],
            $settings['client_secret'],
        );

        $clientBuilder = PaypalServerSdkClientBuilder::init();

        return $clientBuilder->clientCredentialsAuthCredentials($clientCredentialsAuth)
            ->environment($payEnv)
            ->build();
    }

}
