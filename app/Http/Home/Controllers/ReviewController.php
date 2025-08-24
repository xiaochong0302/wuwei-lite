<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Review\ReviewCreate as ReviewCreateService;
use App\Services\Logic\Review\ReviewDelete as ReviewDeleteService;
use App\Services\Logic\Review\ReviewInfo as ReviewInfoService;
use App\Services\Logic\Review\ReviewLike as ReviewLikeService;
use App\Services\Logic\Review\ReviewUpdate as ReviewUpdateService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/review")
 */
class ReviewController extends Controller
{

    /**
     * @Get("/add", name="home.review.add")
     */
    public function addAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="home.review.edit")
     */
    public function editAction($id)
    {
        $service = new ReviewInfoService();

        $review = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('review', $review);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="home.review.info")
     */
    public function infoAction($id)
    {
        $service = new ReviewInfoService();

        $review = $service->handle($id);

        if ($review['deleted'] == 1) {
            $this->notFound();
        }

        return $this->jsonSuccess(['review' => $review]);
    }

    /**
     * @Post("/create", name="home.review.create")
     */
    public function createAction()
    {
        $service = new ReviewCreateService();

        $review = $service->handle();

        $service = new ReviewInfoService();

        $service->handle($review->id);

        $location = $this->url->get(['for' => 'home.uc.reviews']);

        $msg = $this->locale->query('created_ok');

        $content = [
            'target' => 'parent',
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="home.review.update")
     */
    public function updateAction($id)
    {
        $service = new ReviewUpdateService();

        $service->handle($id);

        $service = new ReviewInfoService();

        $service->handle($id);

        $location = $this->url->get(['for' => 'home.uc.reviews']);

        $msg = $this->locale->query('updated_ok');

        $content = [
            'target' => 'parent',
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="home.review.delete")
     */
    public function deleteAction($id)
    {
        $service = new ReviewDeleteService();

        $service->handle($id);

        $msg = $this->locale->query('deleted_ok');

        return $this->jsonSuccess(['msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.review.like")
     */
    public function likeAction($id)
    {
        $service = new ReviewLikeService();

        $data = $service->handle($id);

        return $this->jsonSuccess(['data' => $data]);
    }

}
