<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Models\Chapter as ChapterModel;
use App\Services\Logic\Chapter\ChapterInfo as ChapterInfoService;
use App\Services\Logic\Chapter\ChapterLike as ChapterLikeService;
use App\Services\Logic\Chapter\CommentList as ChapterCommentListService;
use App\Services\Logic\Chapter\Learning as ChapterLearningService;
use App\Services\Logic\Course\BasicInfo as CourseInfoService;
use App\Services\Logic\Course\ChapterList as CourseChapterListService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Traits\Client as ClientTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/chapter")
 */
class ChapterController extends Controller
{

    use ClientTrait;

    /**
     * @Get("/{id}/{slug}", name="home.chapter.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getChapterInfoUrl($id);
            return $this->response->redirect($location);
        }

        $service = new ChapterInfoService();

        $chapter = $service->handle($id);

        if ($chapter['deleted'] == 1) {
            $this->notFound();
        }

        if ($chapter['published'] == 0) {
            $this->notFound();
        }

        $service = new CourseInfoService();

        $course = $service->handle($chapter['course']['id']);

        $service = new CourseChapterListService();

        $catalog = $service->handle($course['id']);

        if ($this->isSearchBot()) {
            $this->view->pick('chapter/bot');
        } elseif ($chapter['me']['owned'] == 0) {
            $this->view->pick('chapter/guest');
        } elseif ($chapter['model'] == ChapterModel::MODEL_VIDEO) {
            $this->view->pick('chapter/video');
        } elseif ($chapter['model'] == ChapterModel::MODEL_ARTICLE) {
            $this->view->pick('chapter/article');
        }

        $title = $this->locale->query('page_lesson_x', ['x' => $chapter['title']]);

        $this->seo->prependTitle($title);
        $this->seo->setKeywords($chapter['keywords']);
        $this->seo->setDescription($chapter['summary']);

        $this->view->setVar('course', $course);
        $this->view->setVar('chapter', $chapter);
        $this->view->setVar('catalog', $catalog);
    }

    /**
     * @Get("/{id:[0-9]+}/comments", name="home.chapter.comments")
     */
    public function commentsAction($id)
    {
        $service = new ChapterCommentListService();

        $pager = $service->handle($id);

        $pager->target = 'comment-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.chapter.like")
     */
    public function likeAction($id)
    {
        $service = new ChapterLikeService();

        $data = $service->handle($id);

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Post("/{id:[0-9]+}/learning", name="home.chapter.learning")
     */
    public function learningAction($id)
    {
        $service = new ChapterLearningService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
