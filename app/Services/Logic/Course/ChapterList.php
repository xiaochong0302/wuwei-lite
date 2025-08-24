<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Course;

use App\Caches\CourseChapterList as CourseChapterListCache;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class ChapterList extends LogicService
{

    use CourseTrait;
    use CourseUserTrait;

    public function handle(int $id): array
    {
        $course = $this->checkCourseCache($id);

        $user = $this->getCurrentUser(true);

        $this->setCourseUser($course, $user);

        return $this->getChapters($course, $user);
    }

    protected function getChapters(CourseModel $course, UserModel $user): array
    {
        $cache = new CourseChapterListCache();

        $chapters = $cache->get($course->id);

        if (count($chapters) == 0) return [];

        if ($user->id > 0) {
            $chapters = $this->handleLoginUserChapters($chapters, $course, $user);
        } else {
            $chapters = $this->handleGuestUserChapters($chapters);
        }

        return $chapters;
    }

    protected function handleLoginUserChapters(array $chapters, CourseModel $course, UserModel $user): array
    {
        $mappings = [];

        if ($this->courseUser) {
            $mappings = $this->getLearningMappings($course->id, $user->id);
        }

        foreach ($chapters as &$chapter) {
            foreach ($chapter['children'] as &$lesson) {
                $owned = ($this->ownedCourse) && $lesson['published'] == 1;
                $lesson['me'] = [
                    'progress' => $mappings[$lesson['id']]['progress'] ?? 0,
                    'duration' => $mappings[$lesson['id']]['duration'] ?? 0,
                    'owned' => $owned ? 1 : 0,
                    'logged' => 1,
                ];
            }
        }

        return $chapters;
    }

    protected function handleGuestUserChapters(array $chapters): array
    {
        foreach ($chapters as &$chapter) {
            foreach ($chapter['children'] as &$lesson) {
                $owned = $this->ownedCourse && $lesson['published'] == 1;
                $lesson['me'] = [
                    'progress' => 0,
                    'duration' => 0,
                    'logged' => 0,
                    'owned' => $owned ? 1 : 0,
                ];
            }
        }

        return $chapters;
    }

    protected function getLearningMappings(int $courseId, int $userId): array
    {
        $courseRepo = new CourseRepo();

        $userLearnings = $courseRepo->findUserLearnings($courseId, $userId);

        if ($userLearnings->count() == 0) return [];

        $mappings = [];

        foreach ($userLearnings as $learning) {
            $mappings[$learning->chapter_id] = [
                'progress' => $learning->progress,
                'duration' => $learning->duration,
                'consumed' => $learning->consumed,
            ];
        }

        return $mappings;
    }

}
