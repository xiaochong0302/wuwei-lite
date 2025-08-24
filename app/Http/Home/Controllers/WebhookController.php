<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Services\Pay\PaypalWebhook;
use App\Services\Pay\StripeWebhook;

/**
 * @RoutePrefix("/webhook")
 */
class WebhookController extends \Phalcon\Mvc\Controller
{

    /**
     * @Post("/paypal", name="home.webhook.paypal")
     */
    public function paypalAction()
    {
        $this->view->disable();

        $service = new PaypalWebhook();

        $result = $service->handle();

        if ($result) {
            $this->response->setStatusCode(200);
        } else {
            $this->response->setStatusCode(500);
        }

        return $this->response;
    }

    /**
     * @Post("/stripe", name="home.webhook.stripe")
     */
    public function stripeAction()
    {
        $this->view->disable();

        $service = new StripeWebhook();

        $result = $service->handle();

        if ($result) {
            $this->response->setStatusCode(200);
        } else {
            $this->response->setStatusCode(500);
        }
    }

}
