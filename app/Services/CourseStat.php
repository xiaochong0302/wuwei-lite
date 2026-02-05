<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services;

use App\Repos\Course as CourseRepo;

class CourseStat extends Service
{

    public function updateLessonCount(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $lessonCount = $courseRepo->countLessons($courseId);

        $course->lesson_count = $lessonCount;

        $course->update();
    }

    public function updateUserCount(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $userCount = $courseRepo->countUsers($courseId);

        $course->user_count = $userCount;

        $course->update();
    }

    public function updateRating(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $rating = $courseRepo->averageRating($courseId);

        $course->rating = $rating;

        $course->update();
    }

}
