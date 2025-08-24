<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Builders;

use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;

class LearningList extends Builder
{

    public function handleCourses(array $relations): array
    {
        $courses = $this->getCourses($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['course'] = $courses[$value['course_id']] ?? null;
        }

        return $relations;
    }

    public function handleChapters(array $relations): array
    {
        $chapters = $this->getChapters($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['chapter'] = $chapters[$value['chapter_id']] ?? null;
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

    protected function getCourses(array $relations): array
    {
        $ids = kg_array_column($relations, 'course_id');

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($courses->toArray() as $course) {
            $result[$course['id']] = $course;
        }

        return $result;
    }

    protected function getChapters(array $relations): array
    {
        $ids = kg_array_column($relations, 'chapter_id');

        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($chapters->toArray() as $chapter) {
            $result[$chapter['id']] = $chapter;
        }

        return $result;
    }

    protected function getUsers(array $relations): array
    {
        $ids = kg_array_column($relations, 'user_id');

        return $this->getShallowUserByIds($ids);
    }

}
