<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic;

use App\Models\Package as PackageModel;
use App\Validators\Package as PackageValidator;

trait PackageTrait
{

    public function checkPackage(int $id): PackageModel
    {
        $validator = new PackageValidator();

        return $validator->checkPackage($id);
    }

    public function checkPackageCache(int $id): PackageModel
    {
        $validator = new PackageValidator();

        return $validator->checkPackageCache($id);
    }

}
