<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Notice;

use App\Models\CourseUser as CourseUserModel;
use App\Models\Task as TaskModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Notice\Email\ReviewRemind as ReviewRemindEmail;
use App\Services\Logic\Service as LogicService;

class ReviewRemind extends LogicService
{

    public function createTask(CourseUserModel $courseUser): void
    {
        if (!$this->emailNoticeEnabled()) return;

        if ($this->hasCreatedTask($courseUser->id)) return;

        $task = new TaskModel();

        $task->item_id = $courseUser->id;
        $task->item_type = TaskModel::TYPE_NOTICE_REVIEW_REMIND;
        $task->priority = TaskModel::PRIORITY_LOW;
        $task->status = TaskModel::STATUS_PENDING;
        $task->max_try_count = 1;

        $task->create();
    }

    public function handleTask(TaskModel $task): bool
    {
        if (!$this->emailNoticeEnabled()) return true;

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findById($task->item_id);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseUser->course_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($courseUser->user_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
            ],
            'course_user' => [
                'duration' => $courseUser->duration,
                'progress' => $courseUser->progress,
            ],
        ];

        $mail = new ReviewRemindEmail();

        return $mail->handle($params);
    }

    protected function hasCreatedTask($courseUserId): bool
    {
        $task = TaskModel::findFirst([
            'conditions' => 'item_id =:item_id: AND item_type = :item_type:',
            'bind' => ['item_id' => $courseUserId, 'item_type' => TaskModel::TYPE_NOTICE_REVIEW_REMIND],
        ]);

        return (bool)$task;
    }

    protected function emailNoticeEnabled(): bool
    {
        $notification = kg_setting('mail', 'notification');

        $settings = json_decode($notification, true);

        return $settings['review_remind'] == 1;
    }

}
