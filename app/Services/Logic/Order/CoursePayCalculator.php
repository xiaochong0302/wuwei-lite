<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Order;

use App\Models\Course as CourseModel;
use App\Models\User as UserModel;

class CoursePayCalculator extends PayCalculator
{

    /**
     * @var CourseModel
     */
    protected CourseModel $course;

    public function __construct(CourseModel $course)
    {
        $this->course = $course;
    }

    public function handleNormalPay(UserModel $user): void
    {
        $this->totalAmount = $this->course->regular_price;

        if ($user->vip == 1) {
            $this->vipDiscountAmount = $this->calculateVipDiscountAmount();
        }

        $this->totalDiscountAmount = $this->vipDiscountAmount;

        $this->payAmount = $this->totalAmount - $this->totalDiscountAmount;
    }

    protected function calculateVipDiscountAmount(): float
    {
        return $this->course->regular_price - $this->course->vip_price;
    }

}
