<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Models\CommentLike as CommentLikeModel;
use App\Models\User as UserModel;
use App\Repos\CommentLike as CommentLikeRepo;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserLimit as UserLimitValidator;

class CommentLike extends LogicService
{

    use CommentTrait;

    public function handle(int $id): array
    {
        $comment = $this->checkComment($id);

        $user = $this->getLoginUser(true);

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLikeLimit($user);

        $likeRepo = new CommentLikeRepo();

        $commentLike = $likeRepo->findCommentLike($comment->id, $user->id);

        if (!$commentLike) {

            $commentLike = new CommentLikeModel();

            $commentLike->comment_id = $comment->id;
            $commentLike->user_id = $user->id;

            $commentLike->create();

        } else {

            $commentLike->deleted = $commentLike->deleted == 1 ? 0 : 1;

            $commentLike->update();
        }

        $this->incrUserDailyCommentLikeCount($user);

        if ($commentLike->deleted == 0) {

            $action = 'do';

            $this->incrCommentLikeCount($comment);

            $this->eventsManager->fire('Comment:afterLike', $this, $comment);

        } else {

            $action = 'undo';

            $this->decrCommentLikeCount($comment);

            $this->eventsManager->fire('Comment:afterUndoLike', $this, $comment);
        }

        return [
            'action' => $action,
            'count' => $comment->like_count,
        ];
    }

    protected function incrCommentLikeCount(CommentModel $comment): void
    {
        $comment->like_count += 1;

        $comment->update();
    }


    protected function decrCommentLikeCount(CommentModel $comment): void
    {
        if ($comment->like_count > 0) {
            $comment->like_count -= 1;
            $comment->update();
        }
    }

    protected function incrUserDailyCommentLikeCount(UserModel $user): void
    {
        $this->eventsManager->fire('UserDailyCounter:incrCommentLikeCount', $this, $user);
    }

}
