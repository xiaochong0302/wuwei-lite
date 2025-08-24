<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Comment;

use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Comment as CommentValidator;

class CommentDelete extends LogicService
{

    use CommentTrait;
    use CountTrait;

    public function handle(int $id): void
    {
        $comment = $this->checkComment($id);

        $user = $this->getLoginUser(true);

        $validator = new CommentValidator();

        $validator->checkOwner($user->id, $comment->owner_id);

        $comment->deleted = 1;

        $comment->update();

        if ($comment->parent_id > 0) {
            $parent = $validator->checkParent($comment->parent_id);
            $this->recountCommentReplies($parent);
        }

        $chapter = $validator->checkChapter($comment->chapter_id);

        $this->recountChapterComments($chapter);

        $this->eventsManager->fire('Comment:afterDelete', $this, $comment);
    }

}
