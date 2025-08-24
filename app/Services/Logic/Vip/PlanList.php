<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Vip;

use App\Repos\Vip as VipRepo;
use App\Services\Logic\Service as LogicService;

class PlanList extends LogicService
{

    public function handle():array
    {
        $vipRepo = new VipRepo();

        $where = [
            'published' => 1,
            'deleted' => 0,
        ];

        $vips = $vipRepo->findAll($where, 'price');

        if ($vips->count() == 0) return [];

        $result = [];

        foreach ($vips as $vip) {
            $result[] = [
                'id' => $vip->id,
                'expiry' => $vip->expiry,
                'price' => $vip->price,
            ];
        }

        return $result;
    }

}
