<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Order;

use App\Models\KgSale as KgSaleModel;
use App\Models\Order as OrderModel;
use App\Models\User as UserModel;
use App\Repos\Order as OrderRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\ShallowUserInfo;
use App\Validators\Order as OrderValidator;

class OrderInfo extends LogicService
{

    public function handle(string $sn): array
    {
        $validator = new OrderValidator();

        $order = $validator->checkOrderBySn($sn);

        $user = $this->getLoginUser();

        return $this->handleOrder($order, $user);
    }

    protected function handleOrder(OrderModel $order, UserModel $user): array
    {
        $order->item_info = $this->handleItemInfo($order);

        $result = [
            'sn' => $order->sn,
            'subject' => $order->subject,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'status' => $order->status,
            'deleted' => $order->deleted,
            'item_id' => $order->item_id,
            'item_type' => $order->item_type,
            'item_info' => $order->item_info,
            'create_time' => $order->create_time,
            'update_time' => $order->update_time,
        ];

        $result['status_history'] = $this->handleStatusHistory($order->id);
        $result['owner'] = $this->handleOwnerInfo($order->owner_id);
        $result['me'] = $this->handleMeInfo($order, $user);

        return $result;
    }

    protected function handleStatusHistory(int $orderId): array
    {
        $orderRepo = new OrderRepo();

        $records = $orderRepo->findStatusHistory($orderId);

        if ($records->count() == 0) return [];

        $result = [];

        foreach ($records as $record) {
            $result[] = [
                'status' => $record->status,
                'create_time' => $record->create_time,
            ];
        }

        return $result;
    }

    protected function handleOwnerInfo(int $userId): array
    {
        $service = new ShallowUserInfo();

        return $service->handle($userId);
    }

    protected function handleMeInfo(OrderModel $order, UserModel $user): array
    {
        $result = [
            'owned' => 0,
            'allow_pay' => 0,
            'allow_cancel' => 0,
            'allow_refund' => 0,
        ];

        if ($user->id == $order->owner_id) {
            $result['owned'] = 1;
        }

        if ($order->status == OrderModel::STATUS_PENDING) {
            $result['allow_pay'] = 1;
            $result['allow_cancel'] = 1;
        }

        if ($order->status == OrderModel::STATUS_FINISHED) {
            if ($order->item_type == KgSaleModel::ITEM_COURSE) {
                $course = $order->item_info['course'];
                $refundTimeOk = $course['refund_expiry_time'] > time();
                if ($refundTimeOk) {
                    $result['allow_refund'] = 1;
                }
            } elseif ($order->item_type == KgSaleModel::ITEM_PACKAGE) {
                $courses = $order->item_info['courses'];
                foreach ($courses as $course) {
                    $refundTimeOk = $course['refund_expiry_time'] > time();
                    if ($refundTimeOk) {
                        $result['allow_refund'] = 1;
                    }
                }
            }
        }

        return $result;
    }

    protected function handleItemInfo(OrderModel $order): array
    {
        $itemInfo = $order->item_info;

        $result = [];

        switch ($order->item_type) {
            case KgSaleModel::ITEM_COURSE:
                $result = $this->handleCourseInfo($itemInfo);
                break;
            case KgSaleModel::ITEM_PACKAGE:
                $result = $this->handlePackageInfo($itemInfo);
                break;
            case KgSaleModel::ITEM_VIP:
                $result = $this->handleVipInfo($itemInfo);
                break;
        }

        return $result;
    }

    protected function handleCourseInfo(array $itemInfo): array
    {
        $cover = $itemInfo['course']['cover'];

        $itemInfo['course']['cover'] = kg_cos_course_cover_url($cover);

        return $itemInfo;
    }

    protected function handlePackageInfo(array $itemInfo): array
    {
        $cover = $itemInfo['package']['cover'];

        $itemInfo['package']['cover'] = kg_cos_package_cover_url($cover);

        $baseUrl = kg_cos_url();

        foreach ($itemInfo['courses'] as &$course) {
            $course['cover'] = $baseUrl . $course['cover'];
        }

        return $itemInfo;
    }

    protected function handleVipInfo(array $itemInfo): array
    {
        $cover = $itemInfo['vip']['cover'];

        $itemInfo['vip']['cover'] = kg_cos_vip_cover_url($cover);

        return $itemInfo;
    }

}
