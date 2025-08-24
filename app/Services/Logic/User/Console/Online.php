<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\User\Console;

use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;

class Online extends LogicService
{

    use ClientTrait;

    public function handle(): void
    {
        $user = $this->getLoginUser();

        $now = time();

        if ($now - $user->active_time < 900) return;

        $user->active_time = $now;

        $user->update();
    }

}
