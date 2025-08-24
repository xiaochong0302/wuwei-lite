<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\Comment as CommentModel;
use App\Models\KgSale;
use App\Models\Order as OrderModel;
use App\Models\OrderStatus as OrderStatusModel;
use App\Models\Review as ReviewModel;
use App\Models\User as UserModel;

class Stat extends Repository
{

    public function countPendingReviews()
    {
        return (int)ReviewModel::count([
            'conditions' => 'published = :published: AND deleted = 0',
            'bind' => ['published' => ReviewModel::PUBLISH_PENDING],
        ]);
    }

    public function countPendingComments()
    {
        return (int)CommentModel::count([
            'conditions' => 'published = :published: AND deleted = 0',
            'bind' => ['published' => CommentModel::PUBLISH_PENDING],
        ]);
    }

    public function countDailyRegisteredUsers($date)
    {
        $startTime = strtotime($date);

        $endTime = $startTime + 86400;

        return (int)UserModel::count([
            'conditions' => 'create_time BETWEEN :start_time: AND :end_time:',
            'bind' => ['start_time' => $startTime, 'end_time' => $endTime],
        ]);
    }

    public function countDailyVipUsers($date)
    {
        $startTime = strtotime($date);

        $endTime = $startTime + 86400;

        return (int)OrderModel::count([
            'conditions' => 'create_time BETWEEN ?1 AND ?2 AND item_type = ?3 AND status = ?4',
            'bind' => [
                1 => $startTime,
                2 => $endTime,
                3 => KgSale::ITEM_VIP,
                4 => OrderModel::STATUS_FINISHED,
            ],
        ]);
    }

    public function sumDailySales($date)
    {
        $sql = "SELECT sum(o.amount) AS total_amount FROM %s AS os JOIN %s AS o ON os.order_id = o.id WHERE os.status = ?1 AND o.create_time BETWEEN ?2 AND ?3";

        $phql = sprintf($sql, OrderStatusModel::class, OrderModel::class);

        $startTime = strtotime($date);

        $endTime = $startTime + 86400;

        $result = $this->modelsManager->executeQuery($phql, [
            1 => OrderModel::STATUS_FINISHED,
            2 => $startTime,
            3 => $endTime,
        ]);

        return (float)$result[0]['total_amount'];
    }

}
