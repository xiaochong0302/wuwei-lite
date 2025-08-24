<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Search;

use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Category as CategoryRepo;
use App\Repos\User as UserRepo;
use Phalcon\Di\Injectable;
use XSDocument;

class CourseDocument extends Injectable
{

    /**
     * 设置文档
     *
     * @param CourseModel $course
     * @return XSDocument
     */
    public function setDocument(CourseModel $course): XSDocument
    {
        $doc = new XSDocument();

        $data = $this->formatDocument($course);

        $doc->setFields($data);

        return $doc;
    }

    /**
     * 格式化文档
     *
     * @param CourseModel $course
     * @return array
     */
    public function formatDocument(CourseModel $course): array
    {
        $teacher = '{}';

        if ($course->teacher_id > 0) {
            $teacher = $this->handleUser($course->teacher_id);
        }

        $category = '{}';

        if ($course->category_id > 0) {
            $category = $this->handleCategory($course->category_id);
        }

        $course->cover = CourseModel::getCoverPath($course->cover);

        return [
            'id' => $course->id,
            'title' => $course->title,
            'slug' => $course->slug,
            'cover' => $course->cover,
            'summary' => $course->summary,
            'keywords' => $course->keywords,
            'category_id' => $course->category_id,
            'teacher_id' => $course->teacher_id,
            'regular_price' => $course->regular_price,
            'vip_price' => $course->vip_price,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'rating' => $course->rating,
            'score' => $course->score,
            'level' => $course->level,
            'create_time' => $course->create_time,
            'update_time' => $course->update_time,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'review_count' => $course->review_count,
            'favorite_count' => $course->favorite_count,
            'category' => $category,
            'teacher' => $teacher,
        ];
    }

    protected function handleUser(int $id): string
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

        $user->avatar = UserModel::getAvatarPath($user->avatar);

        return kg_json_encode([
            'id' => $user->id,
            'name' => $user->name,
            'title' => $user->title,
            'avatar' => $user->avatar,
        ]);
    }

    protected function handleCategory(int $id): string
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        return kg_json_encode([
            'id' => $category->id,
            'name' => $category->name,
        ]);
    }

}
