<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Package as PackageModel;
use App\Repos\Course as CourseRepo;

class CoursePackageList extends Cache
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
        return "course-package-list-{$id}";
    }

    public function getContent($id = null): array
    {
        $courseRepo = new CourseRepo();

        $packages = $courseRepo->findPackages($id);

        if ($packages->count() == 0) {
            return [];
        }

        return $this->handleContent($packages);
    }

    /**
     * @param PackageModel[] $packages
     * @return array
     */
    protected function handleContent($packages): array
    {
        $result = [];

        foreach ($packages as $package) {
            $result[] = [
                'id' => $package->id,
                'title' => $package->title,
                'regular_price' => $package->regular_price,
                'vip_price' => $package->vip_price,
                'course_count' => $package->course_count,
            ];
        }

        return $result;
    }

}
