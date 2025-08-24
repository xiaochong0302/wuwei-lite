<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\CourseQuery as CourseQueryService;
use App\Services\Logic\Course\ChapterList as CourseChapterListService;
use App\Services\Logic\Course\CourseFavorite as CourseFavoriteService;
use App\Services\Logic\Course\CourseInfo as CourseInfoService;
use App\Services\Logic\Course\CourseList as CourseListService;
use App\Services\Logic\Course\LastStudyChapter as LastStudyChapterService;
use App\Services\Logic\Course\PackageList as CoursePackageListService;
use App\Services\Logic\Course\RelatedList as CourseRelatedListService;
use App\Services\Logic\Course\ResourceList as CourseResourceListService;
use App\Services\Logic\Course\ReviewList as CourseReviewListService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Traits\Client as ClientTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/course")
 */
class CourseController extends Controller
{

    use ClientTrait;

    /**
     * @Get("/list", name="home.course.list")
     */
    public function listAction()
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getCourseListUrl();
            return $this->response->redirect($location);
        }

        $service = new CourseQueryService();

        $topCategories = $service->handleTopCategories();
        $subCategories = $service->handleSubCategories();

        $levels = $service->handleLevels();
        $sorts = $service->handleSorts();
        $params = $service->getParams();

        $title = $this->locale->query('page_courses');

        $this->seo->prependTitle($title);

        $this->view->setVar('top_categories', $topCategories);
        $this->view->setVar('sub_categories', $subCategories);
        $this->view->setVar('levels', $levels);
        $this->view->setVar('sorts', $sorts);
        $this->view->setVar('params', $params);
    }

    /**
     * @Get("/pager", name="home.course.pager")
     */
    public function pagerAction()
    {
        $service = new CourseListService();

        $pager = $service->handle();

        $pager->target = 'course-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/pager');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id}/{slug}", name="home.course.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getCourseInfoUrl($id);
            return $this->response->redirect($location);
        }

        $service = new CourseInfoService();

        $course = $service->handle($id);

        if ($course['deleted'] == 1) {
            $this->notFound();
        }

        if ($course['published'] == 0) {
            $this->notFound();
        }

        $service = new CourseChapterListService();

        $chapters = $service->handle($id);

        $title = $this->locale->query('page_course_x', ['x' => $course['title']]);

        $this->seo->prependTitle($title);
        $this->seo->setKeywords($course['keywords']);
        $this->seo->setDescription($course['summary']);

        $this->view->setVar('course', $course);
        $this->view->setVar('chapters', $chapters);
    }

    /**
     * @Get("/{id:[0-9]+}/study", name="home.course.study")
     */
    public function studyAction($id)
    {
        $service = new LastStudyChapterService();

        $chapter = $service->handle($id);

        $location = [
            'for' => 'home.chapter.show',
            'id' => $chapter->id,
            'slug' => $chapter->slug,
        ];

        return $this->response->redirect($location);
    }

    /**
     * @Get("/{id:[0-9]+}/reviews", name="home.course.reviews")
     */
    public function reviewsAction($id)
    {
        $service = new CourseReviewListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-reviews';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/resources", name="home.course.resources")
     */
    public function resourcesAction($id)
    {
        $service = new CourseResourceListService();

        $resources = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('resources', $resources);
    }

    /**
     * @Get("/{id:[0-9]+}/packages", name="home.course.packages")
     */
    public function packagesAction($id)
    {
        $service = new CoursePackageListService();

        $packages = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('packages', $packages);
    }

    /**
     * @Get("/{id:[0-9]+}/related", name="home.course.related")
     */
    public function relatedAction($id)
    {
        $service = new CourseRelatedListService();

        $courses = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('courses', $courses);
    }

    /**
     * @Post("/{id:[0-9]+}/favorite", name="home.course.favorite")
     */
    public function favoriteAction($id)
    {
        $service = new CourseFavoriteService();

        $data = $service->handle($id);

        return $this->jsonSuccess(['data' => $data]);
    }

}
