<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Package;

use App\Models\Package as PackageModel;
use App\Services\Logic\PackageTrait;
use App\Services\Logic\Service as LogicService;

class PackageInfo extends LogicService
{

    use PackageTrait;

    public function handle(int $id): array
    {
        $package = $this->checkPackageCache($id);

        return $this->handlePackage($package);
    }

    protected function handlePackage(PackageModel $package): array
    {
        return [
            'id' => $package->id,
            'title' => $package->title,
            'cover' => $package->cover,
            'summary' => $package->summary,
            'published' => $package->published,
            'deleted' => $package->deleted,
            'regular_price' => $package->regular_price,
            'vip_price' => $package->vip_price,
            'course_count' => $package->course_count,
            'create_time' => $package->create_time,
            'update_time' => $package->update_time,
        ];
    }

}
