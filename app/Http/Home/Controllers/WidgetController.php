<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Caches\FeaturedCourseList as FeaturedCourseListCache;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/widget")
 */
class WidgetController extends Controller
{

    /**
     * @Get("/featured/courses", name="home.widget.featured_courses")
     */
    public function featuredCoursesAction()
    {
        $cache = new FeaturedCourseListCache();

        $courses = $cache->get();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('widget/featured_courses');
        $this->view->setVar('courses', $courses);
    }

}
