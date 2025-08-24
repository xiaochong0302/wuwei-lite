<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Course;

use App\Caches\CourseRatingStat;
use App\Models\Course as CourseModel;
use App\Services\Category as CategoryService;
use App\Services\Logic\ContentTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\ShallowUserInfo as ShallowUserInfoService;

class BasicInfo extends LogicService
{

    use CourseTrait;
    use ContentTrait;

    public function handle(int $id): array
    {
        $course = $this->checkCourse($id);

        return $this->handleBasicInfo($course);
    }

    public function handleBasicInfo(CourseModel $course): array
    {
        $categoryPaths = $this->handleCategoryPaths($course->category_id);
        $ratingStat = $this->handleRatingStat($course->id);
        $teacher = $this->handleTeacherInfo($course->teacher_id);
        $cover = $this->handleCover($course->cover);
        $details = $this->handleContent($course->details);

        return [
            'id' => $course->id,
            'title' => $course->title,
            'slug' => $course->slug,
            'summary' => $course->summary,
            'keywords' => $course->keywords,
            'cover' => $cover,
            'details' => $details,
            'level' => $course->level,
            'rating' => $course->rating,
            'featured' => $course->featured,
            'published' => $course->published,
            'deleted' => $course->deleted,
            'human_verify_enabled' => $course->human_verify_enabled,
            'review_enabled' => $course->review_enabled,
            'comment_enabled' => $course->comment_enabled,
            'regular_price' => $course->regular_price,
            'vip_price' => $course->vip_price,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'package_count' => $course->package_count,
            'resource_count' => $course->resource_count,
            'review_count' => $course->review_count,
            'favorite_count' => $course->favorite_count,
            'create_time' => $course->create_time,
            'update_time' => $course->update_time,
            'category_paths' => $categoryPaths,
            'rating_stat' => $ratingStat,
            'teacher' => $teacher,
        ];
    }

    protected function handleRatingStat(int $courseId):array
    {
        $cache = new CourseRatingStat();

        return $cache->get($courseId);
    }

    protected function handleCategoryPaths(int $categoryId): ?array
    {
        if ($categoryId == 0) return null;

        $service = new CategoryService();

        return $service->getCategoryPaths($categoryId);
    }

    protected function handleTeacherInfo(int $userId): ?array
    {
        if ($userId == 0) return null;

        $service = new ShallowUserInfoService();

        return $service->handle($userId);
    }

}
