<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Payment as PaymentService;
use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/payment")
 */
class PaymentController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Post("/purchase", name="home.payment.purchase")
     */
    public function purchaseAction()
    {
        $service = new PaymentService();

        $purchase = $service->purchase();

        return $this->jsonSuccess(['purchase' => $purchase]);
    }

    /**
     * @Get("/paypal/success", name="home.payment.paypal_success")
     */
    public function paypalSuccessAction()
    {
        $service = new PaymentService();

        $service->paypalSuccess();

        return $this->response->redirect(['for' => 'home.uc.orders']);
    }

    /**
     * @Get("/paypal/cancel", name="home.payment.paypal_cancel")
     */
    public function paypalCancelAction()
    {
        $service = new PaymentService();

        $service->paypalCancel();

        return $this->response->redirect(['for' => 'home.uc.orders']);
    }

    /**
     * @Get("/stripe/success", name="home.payment.stripe_success")
     */
    public function stripeSuccessAction()
    {
        $service = new PaymentService();

        $service->stripeSuccess();

        return $this->response->redirect(['for' => 'home.uc.orders']);
    }

    /**
     * @Get("/stripe/cancel", name="home.payment.stripe_cancel")
     */
    public function stripeCancelAction()
    {
        $service = new PaymentService();

        $service->stripeCancel();

        return $this->response->redirect(['for' => 'home.uc.orders']);
    }

}
