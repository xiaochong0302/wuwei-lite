<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\Chapter as ChapterModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\Learning as LearningModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\ChapterUser as ChapterUserRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Learning as LearningRepo;
use App\Services\Logic\Notice\ReviewRemind as ReviewRemindNotice;
use App\Services\Sync\Learning as LearningSyncService;

class SyncLearningTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $redis = $this->getRedis();

        $sync = new LearningSyncService();

        $syncKey = $sync->getSyncKey();

        $requestIds = $redis->sMembers($syncKey);

        if (!$requestIds) return;

        echo '------ start sync learning task ------' . PHP_EOL;

        foreach ($requestIds as $requestId) {

            $itemKey = $sync->getItemKey($requestId);

            $this->handleLearning($itemKey);

            $redis->sRem($syncKey, $requestId);
        }

        echo '------ end sync learning task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function handleLearning(string $itemKey): void
    {
        $cache = $this->getCache();

        /**
         * @var LearningModel $cacheLearning
         */
        $cacheLearning = $cache->get($itemKey);

        if (!$cacheLearning) return;

        $learningRepo = new LearningRepo();

        $dbLearning = $learningRepo->findByRequestId($cacheLearning->request_id);

        if (!$dbLearning) {

            $cacheLearning->create();

            $this->updateChapterUser($cacheLearning);

        } else {

            $dbLearning->duration += $cacheLearning->duration;
            $dbLearning->position = $cacheLearning->position;
            $dbLearning->active_time = $cacheLearning->active_time;

            $dbLearning->update();

            $this->updateChapterUser($dbLearning);
        }

        $cache->delete($itemKey);
    }

    protected function updateChapterUser(LearningModel $learning): void
    {
        $chapterUserRepo = new ChapterUserRepo();

        $chapterUser = $chapterUserRepo->findChapterUser($learning->chapter_id, $learning->user_id);

        if (!$chapterUser) return;

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($learning->chapter_id);

        if (!$chapter) return;

        $chapterUser->duration += $learning->duration;
        $chapterUser->active_time = $learning->active_time;

        /**
         * 消费规则
         *
         * 1.点播观看时间大于时长30%
         * 2.直播观看时间超过10分钟
         * 3.图文浏览即消费
         */
        if ($chapter->model == ChapterModel::MODEL_VIDEO) {

            $duration = $chapter->attrs['duration'] ?: 300;

            $progress = floor(100 * $chapterUser->duration / $duration);

            /**
             * 过于接近结束位置当作已结束处理，播放位置为起点０
             */
            $playPosition = $duration - $learning->position > 10 ? floor($learning->position) : 0;

            $chapterUser->position = $playPosition;
            $chapterUser->progress = min($progress, 100);
            $chapterUser->consumed = $chapterUser->duration > 0.3 * $duration ? 1 : 0;

        } elseif ($chapter->model == ChapterModel::MODEL_ARTICLE) {

            $chapterUser->consumed = 1;
        }

        $chapterUser->update();

        if ($chapterUser->consumed == 1) {
            $this->updateCourseUser($learning);
        }
    }

    protected function updateCourseUser(LearningModel $learning): void
    {
        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($learning->course_id, $learning->user_id);

        if (!$courseUser) return;

        $courseRepo = new CourseRepo();

        $courseLessons = $courseRepo->findLessons($learning->course_id);

        if ($courseLessons->count() == 0) return;

        $userLearnings = $courseRepo->findUserLearnings($learning->course_id, $learning->user_id);

        if ($userLearnings->count() == 0) return;

        $consumedUserLearnings = [];

        foreach ($userLearnings->toArray() as $userLearning) {
            if ($userLearning['consumed'] == 1) {
                $consumedUserLearnings[] = $userLearning;
            }
        }

        if (count($consumedUserLearnings) == 0) return;

        $duration = 0;

        foreach ($consumedUserLearnings as $userLearning) {
            $duration += $userLearning['duration'];
        }

        $courseLessonIds = kg_array_column($courseLessons->toArray(), 'id');
        $consumedUserLessonIds = kg_array_column($consumedUserLearnings, 'chapter_id');
        $consumedLessonIds = array_intersect($courseLessonIds, $consumedUserLessonIds);

        $totalCount = count($courseLessonIds);
        $consumedCount = count($consumedLessonIds);
        $progress = intval(100 * $consumedCount / $totalCount);

        $courseUser->progress = $progress;
        $courseUser->duration = $duration;
        $courseUser->active_time = $learning->active_time;
        $courseUser->update();

        $this->handleReviewRemindNotice($courseUser);
    }

    protected function handleReviewRemindNotice(CourseUserModel $courseUser): void
    {
        if ($courseUser->reviewed == 1) return;

        if ($courseUser->progress < 80) return;

        $notice = new ReviewRemindNotice();

        $notice->createTask($courseUser);
    }

}
