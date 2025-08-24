<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services;

use App\Models\KgSale as KgSaleModel;
use App\Models\Order as OrderModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;

class Refund extends Service
{

    public function preview(OrderModel $order): array
    {
        return match ($order->item_type) {
            KgSaleModel::ITEM_COURSE => $this->previewCourseRefund($order),
            KgSaleModel::ITEM_PACKAGE => $this->previewPackageRefund($order),
            default => $this->previewOtherRefund($order),
        };
    }

    protected function previewCourseRefund(OrderModel $order): array
    {
        $itemInfo = $order->item_info;

        $itemInfo['course']['cover'] = kg_cos_course_cover_url($itemInfo['course']['cover']);

        $serviceFee = $this->getServiceFee($order);
        $serviceRate = $this->getServiceRate($order);

        $refundRate = 0.00;
        $refundAmount = 0.00;

        if ($itemInfo['course']['refund_expiry_time'] > time()) {
            $refundRate = $this->getCourseRefundPercent($order->item_id, $order->owner_id);
            $refundAmount = round(($order->amount - $serviceFee) * $refundRate, 2);
        }

        $itemInfo['course']['refund_rate'] = $refundRate;
        $itemInfo['course']['refund_amount'] = $refundAmount;

        return [
            'item_type' => $order->item_type,
            'item_info' => $itemInfo,
            'refund_amount' => $refundAmount,
            'service_fee' => $serviceFee,
            'service_rate' => $serviceRate,
        ];
    }

    protected function previewPackageRefund(OrderModel $order): array
    {
        $itemInfo = $order->item_info;

        $serviceFee = $this->getServiceFee($order);
        $serviceRate = $this->getServiceRate($order);

        $totalRegularPrice = 0.00;

        foreach ($itemInfo['courses'] as $course) {
            $totalRegularPrice += $course['regular_price'];
        }

        $totalRefundAmount = 0.00;

        /**
         * 按照占比方式计算退款
         */
        foreach ($itemInfo['courses'] as &$course) {

            $course['cover'] = kg_cos_course_cover_url($course['cover']);

            $refundRate = 0.00;
            $refundAmount = 0.00;

            if ($course['refund_expiry_time'] > time()) {
                $pricePercent = round($course['regular_price'] / $totalRegularPrice, 4);
                $refundRate = $this->getCourseRefundPercent($course['id'], $order->owner_id);
                $refundAmount = round(($order->amount - $serviceFee) * $pricePercent * $refundRate, 2);
                $totalRefundAmount += $refundAmount;
            }

            $course['refund_rate'] = $refundRate;
            $course['refund_amount'] = $refundAmount;
        }

        return [
            'item_type' => $order->item_type,
            'item_info' => $itemInfo,
            'refund_amount' => $totalRefundAmount,
            'service_fee' => $serviceFee,
            'service_rate' => $serviceRate,
        ];
    }

    protected function previewOtherRefund(OrderModel $order): array
    {
        $serviceFee = $this->getServiceFee($order);
        $serviceRate = $this->getServiceRate($order);

        $refundAmount = round($order->amount - $serviceFee, 2);

        return [
            'item_type' => $order->item_type,
            'item_info' => $order->item_info,
            'refund_amount' => $refundAmount,
            'service_fee' => $serviceFee,
            'service_rate' => $serviceRate,
        ];
    }

    protected function getServiceFee(OrderModel $order): float
    {
        $serviceRate = $this->getServiceRate($order);

        $serviceFee = round($order->amount * $serviceRate / 100, 2);

        return $serviceFee >= 0.01 ? $serviceFee : 0.00;
    }

    protected function getServiceRate(OrderModel $order): int
    {
        $alipay = $this->getSettings('payment.paypal');
        $wxpay = $this->getSettings('payment.stripe');

        $serviceRate = 5;

        switch ($order->payment_type) {
            case OrderModel::PAYMENT_PAYPAL:
                $serviceRate = $alipay['service_rate'] ?? $serviceRate;
                break;
            case OrderModel::PAYMENT_STRIPE:
                $serviceRate = $wxpay['service_rate'] ?? $serviceRate;
                break;
        }

        return $serviceRate;
    }

    protected function getCourseRefundPercent(int $courseId, int $userId): float
    {
        $courseRepo = new CourseRepo();

        $courseLessons = $courseRepo->findLessons($courseId);

        if ($courseLessons->count() == 0) return 1.00;

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($courseId, $userId);

        if (!$courseUser) return 1.00;

        $userLearnings = $courseRepo->findUserLearnings($courseId, $userId);

        if ($userLearnings->count() == 0) return 1.00;

        $consumedUserLearnings = $userLearnings->filter(function ($item) {
            if ($item->consumed == 1) return $item;
        });

        if (count($consumedUserLearnings) == 0) return 1.00;

        $courseLessonIds = kg_array_column($courseLessons->toArray(), 'id');
        $consumedUserLessonIds = kg_array_column($consumedUserLearnings, 'chapter_id');
        $consumedLessonIds = array_intersect($courseLessonIds, $consumedUserLessonIds);

        $totalCount = count($courseLessonIds);
        $consumedCount = count($consumedLessonIds);
        $refundCount = $totalCount - $consumedCount;

        return round($refundCount / $totalCount, 4);
    }

}
