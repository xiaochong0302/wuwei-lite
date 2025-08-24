<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Stat as StatService;

/**
 * @RoutePrefix("/admin/stat")
 */
class StatController extends Controller
{

    /**
     * @Get("/sales", name="admin.stat.sales")
     */
    public function salesAction()
    {
        $statService = new StatService();

        $years = $statService->getYearOptions();
        $months = $statService->getMonthOptions();
        $data = $statService->sales();

        $this->view->pick('stat/sales');
        $this->view->setVar('years', $years);
        $this->view->setVar('months', $months);
        $this->view->setVar('data', $data);
    }

    /**
     * @Get("/sellers/best", name="admin.stat.best_sellers")
     */
    public function bestSellersAction()
    {
        $statService = new StatService();

        $orderItemTypes = $statService->getOrderItemTypes();
        $years = $statService->getYearOptions();
        $months = $statService->getMonthOptions();
        $items = $statService->bestSellers();

        $this->view->pick('stat/best_sellers');
        $this->view->setVar('order_item_types', $orderItemTypes);
        $this->view->setVar('years', $years);
        $this->view->setVar('months', $months);
        $this->view->setVar('items', $items);
    }

    /**
     * @Get("/users/registered", name="admin.stat.registered_users")
     */
    public function registeredUsersAction()
    {
        $statService = new StatService();

        $years = $statService->getYearOptions();
        $months = $statService->getMonthOptions();
        $data = $statService->registeredUsers();

        $this->view->pick('stat/registered_users');
        $this->view->setVar('years', $years);
        $this->view->setVar('months', $months);
        $this->view->setVar('data', $data);
    }

    /**
     * @Get("/users/online", name="admin.stat.online_users")
     */
    public function onlineUsersAction()
    {
        $statService = new StatService();

        $years = $statService->getYearOptions();
        $months = $statService->getMonthOptions();
        $data = $statService->onlineUsers();

        $this->view->pick('stat/online_users');
        $this->view->setVar('years', $years);
        $this->view->setVar('months', $months);
        $this->view->setVar('data', $data);
    }

}
