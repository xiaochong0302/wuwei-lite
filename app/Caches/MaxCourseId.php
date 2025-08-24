<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Course as CourseModel;

class MaxCourseId extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 360 * 86400;

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getKey($id = null): string
    {
        return 'max-course-id';
    }

    public function getContent($id = null): int
    {
        $course = CourseModel::findFirst(['order' => 'id DESC']);

        return $course->id ?? 0;
    }

}
