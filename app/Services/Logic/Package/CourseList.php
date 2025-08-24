<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Package;

use App\Caches\PackageCourseList as PackageCourseListCache;
use App\Services\Logic\PackageTrait;
use App\Services\Logic\Service as LogicService;

class CourseList extends LogicService
{

    use PackageTrait;

    public function handle(int $id): array
    {
        $package = $this->checkPackageCache($id);

        $cache = new PackageCourseListCache();

        $courses = $cache->get($package->id);

        return $courses ?: [];
    }

}
