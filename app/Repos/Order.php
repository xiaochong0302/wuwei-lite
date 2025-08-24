<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Order as OrderModel;
use App\Models\OrderStatus as OrderStatusModel;
use App\Models\Refund as RefundModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Paginator\RepositoryInterface;

class Order extends Repository
{

    /**
     * @param array $where
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return RepositoryInterface
     */
    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(OrderModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['sn'])) {
            $builder->andWhere('sn = :sn:', ['sn' => $where['sn']]);
        }

        if (!empty($where['owner_id'])) {
            $builder->andWhere('owner_id = :owner_id:', ['owner_id' => $where['owner_id']]);
        }

        if (!empty($where['item_id'])) {
            $builder->andWhere('item_id = :item_id:', ['item_id' => $where['item_id']]);
        }

        if (!empty($where['item_type'])) {
            if (is_array($where['item_type'])) {
                $builder->inWhere('item_type', $where['item_type']);
            } else {
                $builder->andWhere('item_type = :item_type:', ['item_type' => $where['item_type']]);
            }
        }

        if (!empty($where['coupon_id'])) {
            $builder->andWhere('coupon_id = :coupon_id:', ['coupon_id' => $where['coupon_id']]);
        }

        if (!empty($where['payment_type'])) {
            if (is_array($where['payment_type'])) {
                $builder->inWhere('payment_type', $where['payment_type']);
            } else {
                $builder->andWhere('payment_type = :payment_type:', ['payment_type' => $where['payment_type']]);
            }
        }

        if (!empty($where['status'])) {
            if (is_array($where['status'])) {
                $builder->inWhere('status', $where['status']);
            } else {
                $builder->andWhere('status = :status:', ['status' => $where['status']]);
            }
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        $orderBy = match ($sort) {
            'oldest' => 'id ASC',
            default => 'id DESC',
        };

        $builder->orderBy($orderBy);

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

    /**
     * @param int $id
     * @return OrderModel|Model|bool
     */
    public function findById($id)
    {
        return OrderModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $sn
     * @return OrderModel|Model|bool
     */
    public function findBySn($sn)
    {
        return OrderModel::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);
    }

    /**
     * @param int $userId
     * @param string $itemId
     * @param string $itemType
     * @return OrderModel|Model|bool
     */
    public function findUserLastPendingOrder($userId, $itemId, $itemType)
    {
        $status = OrderModel::STATUS_PENDING;

        return $this->findUserLastStatusOrder($userId, $itemId, $itemType, $status);
    }

    /**
     * @param int $userId
     * @param string $itemId
     * @param string $itemType
     * @return OrderModel|Model|bool
     */
    public function findUserLastDeliveringOrder($userId, $itemId, $itemType)
    {
        $status = OrderModel::STATUS_DELIVERING;

        return $this->findUserLastStatusOrder($userId, $itemId, $itemType, $status);
    }

    /**
     * @param int $userId
     * @param string $itemId
     * @param string $itemType
     * @return OrderModel|Model|bool
     */
    public function findUserLastFinishedOrder($userId, $itemId, $itemType)
    {
        $status = OrderModel::STATUS_FINISHED;

        return $this->findUserLastStatusOrder($userId, $itemId, $itemType, $status);
    }

    /**
     * @param int $userId
     * @param string $itemId
     * @param string $itemType
     * @param int $status
     * @return OrderModel|Model|bool
     */
    public function findUserLastStatusOrder($userId, $itemId, $itemType, $status)
    {
        return OrderModel::findFirst([
            'conditions' => 'owner_id = ?1 AND item_id = ?2 AND item_type = ?3 AND status = ?4',
            'bind' => [1 => $userId, 2 => $itemId, 3 => $itemType, 4 => $status],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|OrderModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return OrderModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|OrderModel[]
     */
    public function findLatestOrders($limit = 10)
    {
        return OrderModel::query()
            ->where('deleted = 0')
            ->orderBy('id DESC')
            ->limit($limit)
            ->execute();
    }

    /**
     * @param int $orderId
     * @return ResultsetInterface|Resultset|RefundModel[]
     */
    public function findRefunds($orderId)
    {
        return RefundModel::query()
            ->where('order_id = :order_id:', ['order_id' => $orderId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $orderId
     * @return ResultsetInterface|Resultset|OrderStatusModel[]
     */
    public function findStatusHistory($orderId)
    {
        return OrderStatusModel::query()
            ->where('order_id = :order_id:', ['order_id' => $orderId])
            ->execute();
    }

    /**
     * @param int $orderId
     * @return RefundModel|Model|bool
     */
    public function findLastRefund($orderId)
    {
        return RefundModel::findFirst([
            'conditions' => 'order_id = :order_id:',
            'bind' => ['order_id' => $orderId],
            'order' => 'id DESC',
        ]);
    }

    public function countOrders()
    {
        return (int)OrderModel::count(['conditions' => 'deleted = 0']);
    }

}
