<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Package as PackageModel;

class MaxPackageId extends Cache
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
        return 'max-package-id';
    }

    public function getContent($id = null): int
    {
        $package = PackageModel::findFirst(['order' => 'id DESC']);

        return $package->id ?? 0;
    }

}
