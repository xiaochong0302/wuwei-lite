<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Verify;

use App\Library\Captcha as AppCaptcha;
use App\Services\Logic\Service as LogicService;

class Captcha extends LogicService
{

    public function handle(): array
    {
        $captcha = new AppCaptcha();

        return $captcha->generate();
    }

}
