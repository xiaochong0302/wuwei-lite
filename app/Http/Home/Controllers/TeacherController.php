<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Teacher\CourseList as TeacherCourseListService;
use App\Services\Logic\Teacher\TeacherList as TeacherListService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Services\Logic\User\UserInfo as UserInfoService;
use App\Traits\Client as ClientTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/teacher")
 */
class TeacherController extends Controller
{

    use ClientTrait;

    /**
     * @Get("/list", name="home.teacher.list")
     */
    public function listAction()
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getTeacherListUrl();
            return $this->response->redirect($location);
        }

        $title = $this->locale->query('page_teachers');

        $this->seo->prependTitle($title);
    }

    /**
     * @Get("/pager", name="home.teacher.pager")
     */
    public function pagerAction()
    {
        $service = new TeacherListService();

        $pager = $service->handle();

        $pager->target = 'teacher-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id}/{name}", name="home.teacher.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getTeacherIndexUrl($id);
            return $this->response->redirect($location);
        }

        $service = new UserInfoService();

        $user = $service->handle($id);

        if ($user['deleted'] == 1) {
            $this->notFound();
        }

        $title = $this->locale->query('page_teacher_x', ['x' => $user['name']]);

        $this->seo->setTitle($title);

        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="home.teacher.courses")
     */
    public function coursesAction($id)
    {
        $service = new TeacherCourseListService();

        $pager = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

}
