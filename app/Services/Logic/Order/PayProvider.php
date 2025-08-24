<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Order;

use App\Services\Logic\Service as LogicService;
use App\Services\Pay\Paypal as PaypalPayService;
use App\Services\Pay\Stripe as StripePayService;

class PayProvider extends LogicService
{

    public function handle(): array
    {
        $service = new PaypalPayService();

        $paypal = $service->getPaypalSettings();

        $service = new StripePayService();

        $stripe = $service->getStripeSettings();

        return [
            'paypal' => ['enabled' => $paypal['enabled']],
            'stripe' => ['enabled' => $stripe['enabled']],
        ];
    }

}
