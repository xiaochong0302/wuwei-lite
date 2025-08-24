<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Repos\Stat as StatRepo;

class SiteTodayStat extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 15 * 60;

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getKey($id = null): string
    {
        return 'site-today-stat';
    }

    public function getContent($id = null): array
    {
        $statRepo = new StatRepo();

        $date = date('Y-m-d');

        $totalSales = $statRepo->sumDailySales($date);
        $vipUsers = $statRepo->countDailyVipUsers($date);
        $newUsers = $statRepo->countDailyRegisteredUsers($date);

        $list = [
            'total_sales' => $totalSales,
            'vip_users' => $vipUsers,
            'new_users' => $newUsers,
        ];

        return array_map(function ($item) {
            return number_format($item);
        }, $list);
    }

}
