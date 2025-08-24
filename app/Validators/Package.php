<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Caches\MaxPackageId as MaxPackageIdCache;
use App\Caches\Package as PackageCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Package as PackageModel;
use App\Repos\Package as PackageRepo;

class Package extends Validator
{

    public function checkPackageCache(int $id): PackageModel
    {
        $this->checkId($id);

        $packageCache = new PackageCache();

        $package = $packageCache->get($id);

        if (!$package) {
            throw new BadRequestException('package.not_found');
        }

        return $package;
    }

    public function checkPackage(int $id): PackageModel
    {
        $this->checkId($id);

        $packageRepo = new PackageRepo();

        $package = $packageRepo->findById($id);

        if (!$package) {
            throw new BadRequestException('package.not_found');
        }

        return $package;
    }

    public function checkId(int $id): void
    {
        $maxIdCache = new MaxPackageIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('package.not_found');
        }
    }

    public function checkCover(string $cover): string
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::image($value)) {
            throw new BadRequestException('package.invalid_cover');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkTitle(string $title): string
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('package.title_too_short');
        }

        if ($length > 120) {
            throw new BadRequestException('package.title_too_long');
        }

        return $value;
    }

    public function checkSummary(string $summary): string
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('package.summary_too_long');
        }

        return $value;
    }

    public function checkregularPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 1 || $value > 999999) {
            throw new BadRequestException('package.invalid_regular_price');
        }

        return $value;
    }

    public function checkVipPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 1 || $value > 999999) {
            throw new BadRequestException('package.invalid_vip_price');
        }

        return $value;
    }

    public function checkPublishStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('package.invalid_publish_status');
        }

        return $status;
    }

}
