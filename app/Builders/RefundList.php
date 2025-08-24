<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Builders;

use App\Models\Refund as RefundModel;
use App\Repos\Order as OrderRepo;

class RefundList extends Builder
{

    public function handleOrders(array $refunds): array
    {
        $orders = $this->getOrders($refunds);

        foreach ($refunds as $key => $refund) {
            $refunds[$key]['order'] = $orders[$refund['order_id']] ?? null;
        }

        return $refunds;
    }

    public function handleUsers(array $refunds): array
    {
        $users = $this->getUsers($refunds);

        foreach ($refunds as $key => $refund) {
            $refunds[$key]['owner'] = $users[$refund['owner_id']] ?? null;
        }

        return $refunds;
    }

    public function handleMeInfo(array $refund): array
    {
        $me = [
            'allow_cancel' => 0,
        ];

        $statusTypes = [
            RefundModel::STATUS_PENDING,
            RefundModel::STATUS_APPROVED,
        ];

        if (in_array($refund['status'], $statusTypes)) {
            $me['allow_cancel'] = 1;
        }

        return $me;
    }

    public function getOrders(array $refunds): array
    {
        $ids = kg_array_column($refunds, 'order_id');

        $orderRepo = new OrderRepo();

        $orders = $orderRepo->findByIds($ids, ['id', 'sn', 'subject', 'amount']);

        $result = [];

        foreach ($orders->toArray() as $order) {
            $result[$order['id']] = $order;
        }

        return $result;
    }

    public function getUsers(array $refunds): array
    {
        $ids = kg_array_column($refunds, 'owner_id');

        return $this->getShallowUserByIds($ids);
    }

}
