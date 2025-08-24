<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Course as CourseModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class IndexPopularCourseList extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 3600;

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getKey($id = null): string
    {
        return 'index-popular-course-list';
    }

    public function getContent($id = null): array
    {
        $limit = 8;

        $courses = $this->findCourses($limit);

        if ($courses->count() == 0) {
            return [];
        }

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

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    protected function findCourses(int $limit = 8)
    {
        return CourseModel::query()
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('user_count DESC')
            ->limit($limit)
            ->execute();
    }

}
