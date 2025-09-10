<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Order;

use App\Models\Course as CourseModel;
use App\Models\KgSale as KgSaleModel;
use App\Models\Package as PackageModel;
use App\Models\Vip as VipModel;
use App\Repos\Package as PackageRepo;
use App\Services\Logic\Service as LogicService;
use App\Validators\Order as OrderValidator;

class OrderConfirm extends LogicService
{

    public function handle(int $itemId, int $itemType): array
    {
        $user = $this->getLoginUser();

        $validator = new OrderValidator();

        $validator->checkItemType($itemType);

        $result = [];

        $result['total_amount'] = 0.00;
        $result['pay_amount'] = 0.00;
        $result['total_discount_amount'] = 0.00;
        $result['vip_discount_amount'] = 0.00;
        $result['other_discount_amount'] = 0.00;

        $result['item_id'] = $itemId;
        $result['item_type'] = $itemType;
        $result['item_info'] = [];

        $calculator = null;

        if ($itemType == KgSaleModel::ITEM_COURSE) {

            $course = $validator->checkCourse($itemId);

            $result['item_info']['course'] = $this->handleCourseInfo($course);

            $calculator = new CoursePayCalculator($course);

            $calculator->handleNormalPay($user);

        } elseif ($itemType == KgSaleModel::ITEM_PACKAGE) {

            $package = $validator->checkPackage($itemId);

            $result['item_info']['package'] = $this->handlePackageInfo($package);

            $calculator = new PackagePayCalculator($package);

            $calculator->handleNormalPay($user);

        } elseif ($itemType == KgSaleModel::ITEM_VIP) {

            $vip = $validator->checkVip($itemId);

            $result['item_info']['vip'] = $this->handleVipInfo($vip);

            $calculator = new VipPayCalculator($vip);

            $calculator->handleNormalPay($user);
        }

        if ($calculator) {
            $result['total_amount'] = $calculator->getTotalAmount();
            $result['pay_amount'] = $calculator->getPayAmount();
            $result['total_discount_amount'] = $calculator->getTotalDiscountAmount();
            $result['vip_discount_amount'] = $calculator->getVipDiscountAmount();
            $result['other_discount_amount'] = $calculator->getOtherDiscountAmount();
        }

        foreach ($result as $key => $value) {
            if (str_ends_with($key, '_amount')) {
                $result[$key] = kg_human_price($value);
            }
        }

        return $result;
    }

    protected function handleCourseInfo(CourseModel $course): array
    {
        return $this->formatCourseInfo($course);
    }

    protected function handlePackageInfo(PackageModel $package): array
    {
        $result = [
            'id' => $package->id,
            'title' => $package->title,
            'cover' => $package->cover,
            'regular_price' => $package->regular_price,
            'vip_price' => $package->vip_price,
        ];

        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($package->id);

        foreach ($courses as $course) {
            $result['courses'][] = $this->formatCourseInfo($course);
        }

        return $result;
    }

    protected function handleVipInfo(VipModel $vip): array
    {
        return [
            'id' => $vip->id,
            'cover' => $vip->cover,
            'expiry' => $vip->expiry,
            'price' => $vip->price,
        ];
    }

    protected function formatCourseInfo(CourseModel $course): array
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'level' => $course->level,
            'regular_price' => $course->regular_price,
            'vip_price' => $course->vip_price,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
        ];
    }

}
