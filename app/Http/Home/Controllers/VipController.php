<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Services\Logic\Vip\CourseList as VipCourseListService;
use App\Services\Logic\Vip\PlanList as VipPlanListService;
use App\Services\Logic\Vip\UserList as VipUserListService;
use App\Traits\Client as ClientTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/vip")
 */
class VipController extends Controller
{

    use ClientTrait;

    /**
     * @Get("/", name="home.vip.index")
     */
    public function indexAction()
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getVipIndexUrl();
            return $this->response->redirect($location);
        }

        $service = new VipPlanListService();

        $plans = $service->handle();

        $title = $this->locale->query('page_vip');

        $this->seo->prependTitle($title);

        $this->view->setVar('plans', $plans);
    }

    /**
     * @Get("/courses", name="home.vip.courses")
     */
    public function coursesAction()
    {
        $type = $this->request->getQuery('type', 'string', 'discount');

        $service = new VipCourseListService();

        $pager = $service->handle($type);

        $pager->target = "tab-{$type}-courses";

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/users", name="home.vip.users")
     */
    public function usersAction()
    {
        $service = new VipUserListService();

        $pager = $service->handle();

        $pager->target = 'tab-users';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

}
