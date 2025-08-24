<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Builders;

use App\Repos\Course as CourseRepo;

class ReviewList extends Builder
{

    public function handleCourses(array $reviews): array
    {
        $courses = $this->getCourses($reviews);

        foreach ($reviews as $key => $review) {
            $reviews[$key]['course'] = $courses[$review['course_id']] ?? null;
        }

        return $reviews;
    }

    public function handleUsers(array $reviews): array
    {
        $users = $this->getUsers($reviews);

        foreach ($reviews as $key => $review) {
            $reviews[$key]['owner'] = $users[$review['owner_id']] ?? null;
        }

        return $reviews;
    }

    public function getCourses(array $reviews): array
    {
        $ids = kg_array_column($reviews, 'course_id');

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($ids, ['id', 'title', 'slug']);

        $result = [];

        foreach ($courses->toArray() as $course) {
            $result[$course['id']] = $course;
        }

        return $result;
    }

    public function getUsers(array $reviews): array
    {
        $ids = kg_array_column($reviews, 'owner_id');

        return $this->getShallowUserByIds($ids);
    }

}
