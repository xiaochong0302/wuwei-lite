<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Course as CourseModel;
use App\Models\KgSale as KgSaleModel;
use App\Models\Order as OrderModel;
use App\Models\Package as PackageModel;
use App\Models\Refund as RefundModel;
use App\Models\Vip as VipModel;
use App\Repos\Order as OrderRepo;

class Order extends Validator
{

    public function checkOrderById(int $id): OrderModel
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($id);

        if (!$order) {
            throw new BadRequestException('order.not_found');
        }

        return $order;
    }

    public function checkOrderBySn(string $sn): OrderModel
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findBySn($sn);

        if (!$order) {
            throw new BadRequestException('order.not_found');
        }

        return $order;
    }

    public function checkPaymentType(int $type): int
    {
        $list = OrderModel::paymentTypes();

        if (!array_key_exists($type, $list)) {
            throw new BadRequestException('order.invalid_payment_type');
        }

        return $type;
    }

    public function checkItemType(int $type): int
    {
        $types = OrderModel::itemTypes();

        if (!array_key_exists($type, $types)) {
            throw new BadRequestException('order.invalid_item_type');
        }

        return $type;
    }

    public function checkCourse(int $id): CourseModel
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkPackage(int $id): PackageModel
    {
        $validator = new Package();

        return $validator->checkPackage($id);
    }

    public function checkVip(int $id): VipModel
    {
        $validator = new Vip();

        return $validator->checkVip($id);
    }

    public function checkAmount(float $amount): float
    {
        $value = $this->filter->sanitize($amount, ['trim', 'float']);

        if ($value < 0.01 || $value > 100000) {
            throw new BadRequestException('order.invalid_amount');
        }

        return $value;
    }

    public function checkStatus(int $status): int
    {
        $list = OrderModel::statusTypes();

        if (!array_key_exists($status, $list)) {
            throw new BadRequestException('order.invalid_status');
        }

        return $status;
    }

    public function checkIfAllowPay(OrderModel $order): void
    {
        if ($order->status != OrderModel::STATUS_PENDING) {
            throw new BadRequestException('order.pay_not_allowed');
        }
    }

    public function checkIfAllowCancel(OrderModel $order): void
    {
        if ($order->status != OrderModel::STATUS_PENDING) {
            throw new BadRequestException('order.cancel_not_allowed');
        }
    }

    public function checkIfAllowRefund(OrderModel $order): void
    {
        if ($order->status != OrderModel::STATUS_FINISHED) {
            throw new BadRequestException('order.refund_not_allowed');
        }

        $types = [
            KgSaleModel::ITEM_COURSE,
            KgSaleModel::ITEM_PACKAGE,
        ];

        if (!in_array($order->item_type, $types)) {
            throw new BadRequestException('order.refund_not_supported');
        }

        $orderRepo = new OrderRepo();

        $refund = $orderRepo->findLastRefund($order->id);

        $scopes = [
            RefundModel::STATUS_PENDING,
            RefundModel::STATUS_APPROVED,
        ];

        if ($refund && in_array($refund->status, $scopes)) {
            throw new BadRequestException('order.refund_request_existed');
        }
    }

    public function checkIfBoughtCourse(int $userId, int $courseId): void
    {
        $orderRepo = new OrderRepo();

        $itemType = KgSaleModel::ITEM_COURSE;

        $order = $orderRepo->findUserLastDeliveringOrder($userId, $courseId, $itemType);

        if ($order) {
            throw new BadRequestException('order.is_delivering');
        }

        $order = $orderRepo->findUserLastFinishedOrder($userId, $courseId, $itemType);

        if ($order && $order->item_info['course']['study_expiry_time'] > time()) {
            throw new BadRequestException('order.has_bought_course');
        }
    }

    public function checkIfBoughtPackage(int $userId, int $packageId): void
    {
        $orderRepo = new OrderRepo();

        $itemType = KgSaleModel::ITEM_PACKAGE;

        $order = $orderRepo->findUserLastDeliveringOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException('order.is_delivering');
        }

        $order = $orderRepo->findUserLastFinishedOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException('order.has_bought_package');
        }
    }

}
