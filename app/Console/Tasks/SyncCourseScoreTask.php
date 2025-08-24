<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Repos\Course as CourseRepo;
use App\Services\Sync\CourseScore as CourseScoreSync;
use App\Services\Utils\CourseScore as CourseScoreService;

class SyncCourseScoreTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $courseIds = $redis->sRandMember($key, 1000);

        if (!$courseIds) return;

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($courseIds);

        if ($courses->count() == 0) return;

        echo '------ start sync course score task ------' . PHP_EOL;

        $service = new CourseScoreService();

        foreach ($courses as $course) {
            $service->handle($course);
        }

        $redis->sRem($key, ...$courseIds);

        echo '------ end sync course score task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function getSyncKey(): string
    {
        $sync = new CourseScoreSync();

        return $sync->getSyncKey();
    }

}
