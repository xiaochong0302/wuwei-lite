<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Services\Logic\Service as LogicService;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserLimit as UserLimitValidator;

class CommentCreate extends LogicService
{

    use CommentDataTrait;
    use CountTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser(true);

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLimit($user);

        $validator = new CommentValidator();

        $chapter = $validator->checkChapter($post['chapter_id']);

        $comment = new CommentModel();

        $data = $this->handlePostData($post);

        $data['chapter_id'] = $chapter->id;
        $data['owner_id'] = $user->id;
        $data['published'] = CommentModel::PUBLISH_PENDING;

        $comment->assign($data);

        $comment->create();

        $this->incrUserDailyCommentCount($user);

        $this->eventsManager->fire('Comment:afterCreate', $this, $comment);

        return $comment;
    }

}
