<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Package as PackageModel;
use App\Repos\Package as PackageRepo;

class Package extends Cache
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
        return "package-{$id}";
    }

    public function getContent($id = null): ?PackageModel
    {
        $packageRepo = new PackageRepo();

        $package = $packageRepo->findById($id);

        return $package ?: null;
    }

}
