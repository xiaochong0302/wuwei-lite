<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Builders\OrderList as OrderListBuilder;
use App\Builders\UserList as UserListBuilder;
use App\Caches\AppInfo as AppInfoCache;
use App\Caches\SiteGlobalStat as SiteGlobalStatCache;
use App\Caches\SiteTodayStat as SiteTodayStatCache;
use App\Library\AppInfo as AppInfo;
use App\Library\Utils\ServerInfo as ServerInfo;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;

class Index extends Service
{

    public function getTopMenus(): array
    {
        $authMenu = new AuthMenu();

        return $authMenu->getTopMenus();
    }

    public function getLeftMenus(): array
    {
        $authMenu = new AuthMenu();

        return $authMenu->getLeftMenus();
    }

    public function getAppInfo(): AppInfo
    {
        $cache = new AppInfoCache();

        $content = $cache->get();

        $appInfo = new AppInfo();

        if (empty($content) || $appInfo->get('version') != $content['version']) {
            $cache->rebuild();
        }

        return $appInfo;
    }

    public function getServerInfo(): array
    {
        return [
            'cpu' => ServerInfo::cpu(),
            'memory' => ServerInfo::memory(),
            'disk' => ServerInfo::disk(),
        ];
    }

    public function getGlobalStat(): array
    {
        $cache = new SiteGlobalStatCache();

        return $cache->get();
    }

    public function getTodayStat(): array
    {
        $cache = new SiteTodayStatCache();

        return $cache->get();
    }

    public function getLatestUsers(): array
    {
        $userRepo = new UserRepo();

        $users = $userRepo->findLatestUsers(5);

        $builder = new UserListBuilder();

        $result = [];

        if ($users->count() > 0) {
            $items = $users->toArray();
            $pipeA = $builder->handleAccounts($items);
            $result = $builder->objects($pipeA);
        }

        return $result;
    }

    public function getLatestOrders(): array
    {
        $orderRepo = new OrderRepo();

        $orders = $orderRepo->findLatestOrders(5);

        $builder = new OrderListBuilder();

        $result = [];

        if ($orders->count() > 0) {
            $items = $orders->toArray();
            $pipeA = $builder->handleUsers($items);
            $result = $builder->objects($pipeA);
        }

        return $result;
    }

}
