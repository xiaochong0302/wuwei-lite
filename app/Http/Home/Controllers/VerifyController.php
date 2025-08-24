<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/verify")
 */
class VerifyController extends Controller
{

    use ResponseTrait;

    /**
     * @Get("/captcha", name="home.verify.captcha")
     */
    public function captchaAction()
    {
        $this->view->pick('verify/captcha');
    }

}
