<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Order;

use App\Models\Package as PackageModel;
use App\Models\User as UserModel;
use App\Repos\Package as PackageRepo;

class PackagePayCalculator extends PayCalculator
{

    /**
     * @var PackageModel
     */
    protected PackageModel $package;

    public function __construct(PackageModel $package)
    {
        $this->package = $package;
    }

    public function handleNormalPay(UserModel $user): void
    {
        $this->totalAmount = $this->calculateTotalAmount();

        $this->payAmount = $this->package->regular_price;

        if ($user->vip == 1) {
            $this->vipDiscountAmount = $this->calculateVipDiscountAmount();
        }

        $this->otherDiscountAmount = $this->totalAmount - $this->package->regular_price;

        $this->totalDiscountAmount = $this->vipDiscountAmount + $this->otherDiscountAmount;

        $this->payAmount = $this->totalAmount - $this->totalDiscountAmount;
    }

    protected function calculateTotalAmount(): float
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($this->package->id);

        $totalAmount = 0.00;

        foreach ($courses as $course) {
            $totalAmount += $course->regular_price;
        }

        return $totalAmount;
    }

    protected function calculateVipDiscountAmount(): float
    {
        return $this->package->regular_price - $this->package->vip_price;
    }

}
