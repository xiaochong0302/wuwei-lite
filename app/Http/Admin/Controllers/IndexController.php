<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Index as IndexService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/admin")
 */
class IndexController extends Controller
{

    /**
     * @Get("/", name="admin.index")
     */
    public function indexAction()
    {
        $indexService = new IndexService();

        $topMenus = $indexService->getTopMenus();
        $leftMenus = $indexService->getLeftMenus();
        $appInfo = $indexService->getAppInfo();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->setVar('top_menus', $topMenus);
        $this->view->setVar('left_menus', $leftMenus);
        $this->view->setVar('app_info', $appInfo);
    }

    /**
     * @Get("/main", name="admin.main")
     */
    public function mainAction()
    {
        $indexService = new IndexService();

        $globalStat = $indexService->getGlobalStat();
        $todayStat = $indexService->getTodayStat();

        $appInfo = $indexService->getAppInfo();
        $serverInfo = $indexService->getServerInfo();

        $latestUsers = $indexService->getLatestUsers();
        $latestOrders = $indexService->getLatestOrders();

        $this->view->setVar('global_stat', $globalStat);
        $this->view->setVar('today_stat', $todayStat);
        $this->view->setVar('app_info', $appInfo);
        $this->view->setVar('server_info', $serverInfo);
        $this->view->setVar('latest_users', $latestUsers);
        $this->view->setVar('latest_orders', $latestOrders);
    }

    /**
     * @Get("/phpinfo", name="admin.phpinfo")
     */
    public function phpinfoAction()
    {
        return phpinfo();
    }

}
