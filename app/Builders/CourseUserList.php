<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Builders;

use App\Repos\Course as CourseRepo;

class CourseUserList extends Builder
{

    public function handleCourses(array $relations): array
    {
        $courses = $this->getCourses($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['course'] = $courses[$value['course_id']] ?? null;
        }

        return $relations;
    }

    public function handleUsers(array $relations): array
    {
        $users = $this->getUsers($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['user'] = $users[$value['user_id']] ?? null;
        }

        return $relations;
    }

    public function getCourses(array $relations): array
    {
        $ids = kg_array_column($relations, 'course_id');

        $courseRepo = new CourseRepo();

        $columns = [
            'id', 'title', 'slug', 'cover', 'rating', 'level', 'review_enabled',
            'user_count', 'lesson_count', 'review_count', 'favorite_count',
        ];

        $courses = $courseRepo->findByIds($ids, $columns);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($courses->toArray() as $course) {
            $course['cover'] = $baseUrl . $course['cover'];
            $course['attrs'] = json_decode($course['attrs'], true);
            $result[$course['id']] = $course;
        }

        return $result;
    }

    public function getUsers(array $relations): array
    {
        $ids = kg_array_column($relations, 'user_id');

        return $this->getShallowUserByIds($ids);
    }

}
