<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Package\CourseList as PackageCourseListService;
use App\Services\Logic\Package\PackageInfo as PackageInfoService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/package")
 */
class PackageController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="home.package.show")
     */
    public function showAction($id)
    {
        $service = new PackageInfoService();

        $package = $service->handle($id);

        if ($package['deleted'] == 1) {
            $this->notFound();
        }

        if ($package['published'] == 0) {
            $this->notFound();
        }

        $service = new PackageCourseListService();

        $courses = $service->handle($id);

        $this->seo->prependTitle(['套餐', $package['title']]);

        $this->view->setVar('package', $package);
        $this->view->setVar('courses', $courses);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="home.package.info")
     */
    public function infoAction($id)
    {
        $service = new PackageInfoService();

        $package = $service->handle($id);

        return $this->jsonSuccess(['package' => $package]);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="home.package.courses")
     */
    public function coursesAction($id)
    {
        $service = new PackageCourseListService();

        $courses = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->setVar('courses', $courses);
    }

}
