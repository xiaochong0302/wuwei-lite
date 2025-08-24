<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Builders;

use App\Caches\CategoryAllList as CategoryAllListCache;
use App\Repos\User as UserRepo;

class CourseList extends Builder
{

    public function handleCategories(array $courses): array
    {
        $categories = $this->getCategories();

        foreach ($courses as $key => $course) {
            $courses[$key]['category'] = $categories[$course['category_id']] ?? null;
        }

        return $courses;
    }

    public function handleTeachers(array $courses): array
    {
        $teachers = $this->getTeachers($courses);

        foreach ($courses as $key => $course) {
            $courses[$key]['teacher'] = $teachers[$course['teacher_id']] ?? null;
        }

        return $courses;
    }

    public function getTeachers(array $courses): array
    {
        $ids = kg_array_column($courses, 'teacher_id');

        return $this->getShallowUserByIds($ids);
    }

    public function getCategories(): array
    {
        $cache = new CategoryAllListCache();

        $items = $cache->get();

        if (empty($items)) return [];

        $result = [];

        foreach ($items as $item) {
            $result[$item['id']] = [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }

        return $result;
    }

}
