<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;

class Course extends Cache
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
        return "course-{$id}";
    }

    public function getContent($id = null): ?CourseModel
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($id);

        return $course ?: null;
    }

}
