<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Builders;

use App\Models\KgSale as KgSaleModel;
use App\Models\Order as OrderModel;

class OrderList extends Builder
{

    /**
     * @var string
     */
    protected string $imgBaseUrl;

    public function __construct()
    {
        $this->imgBaseUrl = kg_cos_url();
    }

    /**
     * @param array $orders
     * @return array
     */
    public function handleUsers(array $orders): array
    {
        $users = $this->getUsers($orders);

        foreach ($orders as $key => $order) {
            $orders[$key]['owner'] = $users[$order['owner_id']] ?? null;
        }

        return $orders;
    }

    /**
     * @param array $orders
     * @return array
     */
    public function handleItems(array $orders): array
    {
        foreach ($orders as $key => $order) {
            $itemInfo = $this->handleItemInfo($order);
            $orders[$key]['item_info'] = $itemInfo;
        }

        return $orders;
    }

    /**
     * @param array $order
     * @return array
     */
    public function handleItemInfo(array $order): array
    {
        $itemInfo = [];

        switch ($order['item_type']) {
            case KgSaleModel::ITEM_COURSE:
                $itemInfo = $this->handleCourseInfo($order['item_info']);
                break;
            case KgSaleModel::ITEM_PACKAGE:
                $itemInfo = $this->handlePackageInfo($order['item_info']);
                break;
            case KgSaleModel::ITEM_VIP:
                $itemInfo = $this->handleVipInfo($order['item_info']);
                break;
        }

        return $itemInfo;
    }

    /**
     * @param array $order
     * @return array
     */
    public function handleMeInfo(array $order): array
    {
        $me = [
            'allow_pay' => 0,
            'allow_cancel' => 0,
            'allow_refund' => 0,
        ];

        $payStatusOk = $order['status'] == OrderModel::STATUS_PENDING ? 1 : 0;
        $cancelStatusOk = $order['status'] == OrderModel::STATUS_PENDING ? 1 : 0;
        $refundStatusOk = $order['status'] == OrderModel::STATUS_FINISHED ? 1 : 0;

        if ($order['item_type'] == KgSaleModel::ITEM_COURSE) {

            $course = $order['item_info']['course'];

            $refundTimeOk = $course['refund_expiry_time'] > time();

            $me['allow_refund'] = $refundStatusOk && $refundTimeOk ? 1 : 0;

        } elseif ($order['item_type'] == KgSaleModel::ITEM_PACKAGE) {

            $courses = $order['item_info']['courses'];

            $refundTimeOk = false;

            foreach ($courses as $course) {
                if ($course['refund_expiry_time'] > time()) {
                    $refundTimeOk = true;
                }
            }

            $me['allow_refund'] = $refundStatusOk && $refundTimeOk ? 1 : 0;
        }

        if ($payStatusOk == 1) {
            $me['allow_pay'] = 1;
        }

        if ($cancelStatusOk == 1) {
            $me['allow_cancel'] = 1;
        }

        return $me;
    }

    /**
     * @param string|array $itemInfo
     * @return array
     */
    protected function handleCourseInfo(string|array $itemInfo): array
    {
        if (!empty($itemInfo) && is_string($itemInfo)) {
            $itemInfo = json_decode($itemInfo, true);
            $itemInfo['course']['cover'] = $this->imgBaseUrl . $itemInfo['course']['cover'];
        }

        return $itemInfo;
    }

    /**
     * @param string|array $itemInfo
     * @return mixed
     */
    protected function handlePackageInfo(string|array $itemInfo): array
    {
        if (!empty($itemInfo) && is_string($itemInfo)) {
            $itemInfo = json_decode($itemInfo, true);
            foreach ($itemInfo['courses'] as $key => $course) {
                $itemInfo['courses'][$key]['cover'] = $this->imgBaseUrl . $course['cover'];
            }
        }

        return $itemInfo;
    }

    /**
     * @param string|array $itemInfo
     * @return mixed
     */
    protected function handleVipInfo(string|array $itemInfo)
    {
        if (!empty($itemInfo) && is_string($itemInfo)) {
            $itemInfo = json_decode($itemInfo, true);
            $itemInfo['vip']['cover'] = $this->imgBaseUrl . $itemInfo['vip']['cover'];
        }

        return $itemInfo;
    }

    /**
     * @param array $orders
     * @return array
     */
    protected function getUsers(array $orders): array
    {
        $ids = kg_array_column($orders, 'owner_id');

        return $this->getShallowUserByIds($ids);
    }

}
