<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Comment;

use App\Models\Chapter as ChapterModel;
use App\Models\Comment as CommentModel;
use App\Models\User as UserModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Comment as CommentRepo;
use Phalcon\Di\Di as Di;
use Phalcon\Events\Manager as EventsManager;

trait CountTrait
{

    protected function recountChapterComments(ChapterModel $chapter): void
    {
        $chapterRepo = new ChapterRepo();

        $commentCount = $chapterRepo->countComments($chapter->id);

        $chapter->comment_count = $commentCount;

        $chapter->update();
    }

    protected function recountCommentReplies(CommentModel $comment): void
    {
        $commentRepo = new CommentRepo();

        $replyCount = $commentRepo->countReplies($comment->id);

        $comment->reply_count = $replyCount;

        $comment->update();
    }

    protected function incrUserDailyCommentCount(UserModel $user): void
    {
        /**
         * @var EventsManager $eventsManager
         */
        $eventsManager = Di::getDefault()->get('eventsManager');

        $eventsManager->fire('UserDailyCounter:incrCommentCount', $this, $user);
    }

}
