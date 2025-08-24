<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Comment\CommentCreate as CommentCreateService;
use App\Services\Logic\Comment\CommentDelete as CommentDeleteService;
use App\Services\Logic\Comment\CommentInfo as CommentInfoService;
use App\Services\Logic\Comment\CommentLike as CommentLikeService;
use App\Services\Logic\Comment\CommentReply as CommentReplyService;
use App\Services\Logic\Comment\ReplyList as ReplyListService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/comment")
 */
class CommentController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/replies", name="home.comment.replies")
     */
    public function repliesAction($id)
    {
        $service = new ReplyListService();

        $pager = $service->handle($id);

        $pager->target = "reply-list-{$id}";

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="home.comment.info")
     */
    public function infoAction($id)
    {
        $service = new CommentInfoService();

        $comment = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->setVar('comment', $comment);
    }

    /**
     * @Post("/create", name="home.comment.create")
     */
    public function createAction()
    {
        $service = new CommentCreateService();

        $comment = $service->handle();

        $service = new CommentInfoService();

        $comment = $service->handle($comment->id);

        return $this->jsonSuccess(['comment' => $comment]);
    }

    /**
     * @Post("/{id:[0-9]+}/reply", name="home.comment.reply")
     */
    public function replyAction($id)
    {
        $service = new CommentReplyService();

        $comment = $service->handle($id);

        $service = new CommentInfoService();

        $comment = $service->handle($comment->id);

        return $this->jsonSuccess(['comment' => $comment]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.comment.like")
     */
    public function likeAction($id)
    {
        $service = new CommentLikeService();

        $data = $service->handle($id);

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="home.comment.delete")
     */
    public function deleteAction($id)
    {
        $service = new CommentDeleteService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
