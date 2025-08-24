<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Course as CourseModel;
use App\Repos\Package as PackageRepo;

class PackageCourseList extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 86400;

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getKey($id = null): string
    {
        return "package-course-list-{$id}";
    }

    public function getContent($id = null): array
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($id);

        if ($courses->count() == 0) {
            return [];
        }

        return $this->handleContent($courses);
    }

    /**
     * @param CourseModel[] $courses
     * @return array
     */
    public function handleContent($courses): array
    {
        $result = [];

        foreach ($courses as $course) {
            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'cover' => $course->cover,
                'level' => $course->level,
                'rating' => round($course->rating, 1),
                'regular_price' => $course->regular_price,
                'vip_price' => $course->vip_price,
                'user_count' => $course->user_count,
                'lesson_count' => $course->lesson_count,
                'review_count' => $course->review_count,
                'favorite_count' => $course->favorite_count,
            ];
        }

        return $result;
    }

}
