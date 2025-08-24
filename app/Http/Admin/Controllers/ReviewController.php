<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Review as ReviewService;

/**
 * @RoutePrefix("/admin/review")
 */
class ReviewController extends Controller
{

    /**
     * @Get("/search", name="admin.review.search")
     */
    public function searchAction()
    {
        $reviewService = new ReviewService();

        $publishTypes = $reviewService->getPublishTypes();

        $this->view->setVar('publish_types', $publishTypes);
    }

    /**
     * @Get("/list", name="admin.review.list")
     */
    public function listAction()
    {
        $reviewService = new ReviewService();

        $pager = $reviewService->getReviews();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.review.edit")
     */
    public function editAction($id)
    {
        $reviewService = new ReviewService();

        $publishTypes = $reviewService->getPublishTypes();
        $review = $reviewService->getReview($id);

        $this->view->setVar('publish_types', $publishTypes);
        $this->view->setVar('review', $review);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.review.update")
     */
    public function updateAction($id)
    {
        $reviewService = new ReviewService();

        $reviewService->updateReview($id);

        $content = [
            'msg' => $this->locale->query('updated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.review.delete")
     */
    public function deleteAction($id)
    {
        $reviewService = new ReviewService();

        $reviewService->deleteReview($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.review.restore")
     */
    public function restoreAction($id)
    {
        $reviewService = new ReviewService();

        $reviewService->restoreReview($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('restored_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/moderate/batch", name="admin.review.batch_moderate")
     */
    public function batchModerateAction()
    {
        $reviewService = new ReviewService();

        $reviewService->batchModerate();

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('moderated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/delete/batch", name="admin.review.batch_delete")
     */
    public function batchDeleteAction()
    {
        $reviewService = new ReviewService();

        $reviewService->batchDelete();

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
