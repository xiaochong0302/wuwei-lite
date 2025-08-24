<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use Phalcon\Mvc\Model\ResultsetInterface;

class SyncCourseStatTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $courses = $this->findCourses();

        echo sprintf('pending courses: %s', $courses->count()) . PHP_EOL;

        if ($courses->count() == 0) return;

        echo '------ start sync course stat task ------' . PHP_EOL;

        foreach ($courses as $course) {
            $this->recountUsers($course);
        }

        echo '------ end sync course stat task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function recountUsers(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $userCount = $courseRepo->countUsers($course->id);

        $course->user_count = $userCount;

        $course->update();
    }

    /**
     * @return ResultsetInterface|CourseModel[]
     */
    protected function findCourses()
    {
        return CourseModel::query()
            ->where('published = 1')
            ->execute();
    }

}
