<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Deliver;

use App\Caches\User as UserCache;
use App\Models\User as UserModel;
use App\Models\Vip as VipModel;
use App\Services\Logic\Service as LogicService;

class VipDeliver extends LogicService
{

    public function handle(VipModel $vip, UserModel $user): void
    {
        $baseTime = $user->vip_expiry_time > time() ? $user->vip_expiry_time : time();

        $user->vip_expiry_time = strtotime("+{$vip->expiry} months", $baseTime);

        $user->vip = 1;

        $user->update();

        $cache = new UserCache();

        $cache->rebuild($user->id);
    }

}
