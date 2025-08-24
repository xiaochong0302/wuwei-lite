<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Course;

use App\Caches\CourseChapterList as CourseChapterListCache;
use App\Models\Chapter as ChapterModel;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class LastStudyChapter extends LogicService
{

    use CourseTrait;
    use ChapterTrait;

    /**
     * @var array
     */
    protected array $courseChapters = [];

    public function handle(int $id): ChapterModel
    {
        $course = $this->checkCourseCache($id);

        $user = $this->getLoginUser(true);

        $cache = new CourseChapterListCache();

        $this->courseChapters = $cache->get($course->id);

        $courseRepo = new CourseRepo();

        $chapterUser = $courseRepo->findLastChapterUser($course->id, $user->id);

        // 不存在学习记录，返回第一课时
        if (!$chapterUser) {
            return $this->getFirstLesson();
        }

        // 尚未完成课时，继续学习
        if ($chapterUser->consumed == 0) {
            return $this->getFinalLesson($chapterUser->chapter_id);
        }

        $lessonId = $this->getNextLessonId($chapterUser->chapter_id);

        return $this->getFinalLesson($lessonId);
    }

    protected function getFirstLesson(): ?ChapterModel
    {
        foreach ($this->courseChapters as $chapter) {
            foreach ($chapter['children'] as $lesson) {
                if ($lesson['published'] == 1) {
                    return $this->checkChapter($lesson['id']);
                }
            }
        }

        return null;
    }

    protected function getFinalLesson(int $lessonId): ?ChapterModel
    {
        $chapter = $this->checkChapter($lessonId);

        if ($chapter->published == 1 && $chapter->deleted == 0) {
            return $chapter;
        }

        return null;
    }

    protected function getNextLessonId(int $lessonId): int|null
    {
        $chapterIndex = $this->getChapterIndex($lessonId);
        $lessonIndex = $this->getLessonIndex($lessonId);

        foreach ($this->courseChapters as $i => $chapter) {
            foreach ($chapter['children'] as $j => $lesson) {
                if ($lesson['published'] == 1) {
                    if ($i == $chapterIndex && $j > $lessonIndex) {
                        return $lesson['id'];
                    } elseif ($i > $chapterIndex) {
                        return $lesson['id'];
                    }
                }
            }
        }

        return null;
    }

    protected function getChapterIndex(int $lessonId): int|null
    {
        foreach ($this->courseChapters as $index => $chapter) {
            foreach ($chapter['children'] as $lesson) {
                if ($lesson['id'] == $lessonId) {
                    return $index;
                }
            }
        }

        return null;
    }

    protected function getLessonIndex(int $lessonId): int|null
    {
        foreach ($this->courseChapters as $chapter) {
            foreach ($chapter['children'] as $index => $lesson) {
                if ($lesson['id'] == $lessonId) {
                    return $index;
                }
            }
        }

        return null;
    }

}
