<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserLimit as UserLimitValidator;

class CommentReply extends LogicService
{

    use CommentDataTrait;
    use CommentTrait;
    use CountTrait;

    public function handle(int $id): CommentModel
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser(true);

        $comment = $this->checkComment($id);

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLimit($user);

        $parent = $comment;

        $validator = new CommentValidator();

        $data = $this->handlePostData($post);

        $data['owner_id'] = $user->id;
        $data['parent_id'] = $parent->id;
        $data['chapter_id'] = $comment->chapter_id;
        $data['published'] = CommentModel::PUBLISH_PENDING;

        /**
         * 子评论中回复用户
         */
        if ($comment->parent_id > 0) {
            $parent = $validator->checkParent($comment->parent_id);
            $data['parent_id'] = $parent->id;
            $data['to_user_id'] = $comment->owner_id;
        }

        $reply = new CommentModel();

        $reply->assign($data);

        $reply->create();

        $moderation = new CommentModeration();

        $moderation->addTask($comment->id);

        $this->incrUserDailyCommentCount($user);

        $this->eventsManager->fire('Comment:afterReply', $this, $reply);

        return $reply;
    }

}
