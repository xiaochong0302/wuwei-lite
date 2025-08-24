<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Comment as CommentService;

/**
 * @RoutePrefix("/admin/comment")
 */
class CommentController extends Controller
{

    /**
     * @Get("/search", name="admin.comment.search")
     */
    public function searchAction()
    {
        $commentService = new CommentService();

        $publishTypes = $commentService->getPublishTypes();

        $this->view->setVar('publish_types', $publishTypes);
    }

    /**
     * @Get("/list", name="admin.comment.list")
     */
    public function listAction()
    {
        $commentService = new CommentService();

        $pager = $commentService->getComments();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.comment.edit")
     */
    public function editAction($id)
    {
        $commentService = new CommentService();

        $publishTypes = $commentService->getPublishTypes();
        $comment = $commentService->getComment($id);

        $this->view->setVar('publish_types', $publishTypes);
        $this->view->setVar('comment', $comment);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.comment.update")
     */
    public function updateAction($id)
    {
        $commentService = new CommentService();

        $commentService->updateComment($id);

        $content = [
            'msg' => $this->locale->query('updated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.comment.delete")
     */
    public function deleteAction($id)
    {
        $commentService = new CommentService();

        $commentService->deleteComment($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.comment.restore")
     */
    public function restoreAction($id)
    {
        $commentService = new CommentService();

        $commentService->restoreComment($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('restored_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/moderate/batch", name="admin.comment.batch_moderate")
     */
    public function batchModerateAction()
    {
        $commentService = new CommentService();

        $commentService->batchModerate();

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('moderated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/delete/batch", name="admin.comment.batch_delete")
     */
    public function batchDeleteAction()
    {
        $commentService = new CommentService();

        $commentService->batchDelete();

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

}
