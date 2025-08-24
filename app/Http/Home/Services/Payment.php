<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Services;

use App\Models\Order as OrderModel;
use App\Services\Pay\Paypal as PaypalService;
use App\Services\Pay\Stripe as StripeService;
use App\Validators\Order as OrderValidator;

class Payment extends Service
{

    public function purchase(): array
    {
        $sn = $this->request->getPost('sn', ['trim', 'string']);
        $channel = $this->request->getPost('channel', ['trim', 'int']);

        $orderValidator = new OrderValidator();

        $order = $orderValidator->checkOrderBySn($sn);

        $orderValidator->checkPaymentType($channel);

        $orderValidator->checkIfAllowPay($order);

        $response = [
            'status' => 'redirect',
            'message' => '',
            'redirect_url' => '',
        ];

        if ($channel == OrderModel::PAYMENT_PAYPAL) {
            $paypal = new PaypalService();
            $response = $paypal->purchase($order);
        } elseif ($channel == OrderModel::PAYMENT_STRIPE) {
            $stripe = new StripeService();
            $response = $stripe->purchase($order);
        }

        return $response;
    }

    public function paypalSuccess(): void
    {
        $token = $this->request->getQuery('token', ['trim', 'string'], '');
        $sn = $this->request->getQuery('sn', ['trim', 'string'], '');

        $service = new PaypalService();

        $service->completePurchase($token, $sn);
    }

    public function paypalCancel(): void
    {
        $token = $this->request->getQuery('token', ['trim', 'string'], '');
        $sn = $this->request->getQuery('sn', ['trim', 'string'], '');

        $service = new PaypalService();

        $service->cancelPurchase($token, $sn);
    }

    public function stripeSuccess(): void
    {
        $sessionId = $this->request->getQuery('session_id', ['trim', 'string'], '');
        $sn = $this->request->getQuery('sn', ['trim', 'string'], '');

        $service = new StripeService();

        $service->completePurchase($sessionId, $sn);
    }

    public function stripeCancel(): void
    {
        $sn = $this->request->getQuery('sn', ['trim', 'string'], '');

        $service = new StripeService();

        $service->cancelPurchase($sn);
    }

}
