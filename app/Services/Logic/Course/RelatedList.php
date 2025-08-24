<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Course;

use App\Caches\CourseRelatedList as CourseRelatedListCache;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class RelatedList extends LogicService
{

    use CourseTrait;

    public function handle(int $id): array
    {
        $course = $this->checkCourseCache($id);

        $cache = new CourseRelatedListCache();

        $result = $cache->get($course->id);

        return $result ?: [];
    }

}
