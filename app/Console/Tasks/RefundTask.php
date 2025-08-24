<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\KgSale as KgSaleModel;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Refund as RefundRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Notice\RefundFinish as RefundFinishNotice;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class RefundTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $tasks = $this->findTasks(30);

        echo sprintf('pending tasks: %s', $tasks->count()) . PHP_EOL;

        if ($tasks->count() == 0) return;

        echo '------ start refund task ------' . PHP_EOL;

        $orderRepo = new OrderRepo();
        $refundRepo = new RefundRepo();

        foreach ($tasks as $task) {

            $refund = $refundRepo->findById($task->item_id);
            $order = $orderRepo->findById($refund->order_id);

            if ($refund->status != RefundModel::STATUS_APPROVED) {
                $task->status = TaskModel::STATUS_CANCELED;
                $task->update();
                continue;
            }

            try {

                $this->db->begin();

                $this->handleOrderRefund($order);

                $refund->status = RefundModel::STATUS_FINISHED;
                $refund->update();

                $order->status = OrderModel::STATUS_REFUNDED;
                $order->update();

                $task->status = TaskModel::STATUS_FINISHED;
                $task->update();

                $this->db->commit();

                $this->handleRefundFinishNotice($refund);

            } catch (\Exception $e) {

                $this->db->rollback();

                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count > $task->max_try_count) {
                    $task->status = TaskModel::STATUS_FAILED;
                }

                $task->update();

                $logger = $this->getLogger('refund');

                $logger->error('Refund Task Exception: ' . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }

            if ($task->status == TaskModel::STATUS_FAILED) {
                $refund->status = RefundModel::STATUS_FAILED;
                $refund->update();
            }
        }

        echo '------ end refund task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    /**
     * 处理订单退款
     *
     * @param OrderModel $order
     */
    protected function handleOrderRefund(OrderModel $order): void
    {
        switch ($order->item_type) {
            case KgSaleModel::ITEM_COURSE:
                $this->handleCourseOrderRefund($order);
                break;
            case KgSaleModel::ITEM_PACKAGE:
                $this->handlePackageOrderRefund($order);
                break;
            case KgSaleModel::ITEM_VIP:
                $this->handleVipOrderRefund($order);
                break;
        }
    }

    /**
     * 处理课程订单退款
     *
     * @param OrderModel $order
     */
    protected function handleCourseOrderRefund(OrderModel $order): void
    {
        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($order->item_id, $order->owner_id);

        if ($courseUser) {
            $courseUser->deleted = 1;
            if ($courseUser->update() === false) {
                throw new \RuntimeException('Delete Course User Failed');
            }
        }
    }

    /**
     * 处理套餐订单退款
     *
     * @param OrderModel $order
     */
    protected function handlePackageOrderRefund(OrderModel $order): void
    {
        $courseUserRepo = new CourseUserRepo();

        $itemInfo = $order->item_info;

        foreach ($itemInfo['courses'] as $course) {

            $courseUser = $courseUserRepo->findCourseUser($course['id'], $order->owner_id);

            if ($courseUser) {
                $courseUser->deleted = 1;
                if ($courseUser->update() === false) {
                    throw new \RuntimeException('Delete Course User Failed');
                }
            }
        }
    }

    /**
     * 处理会员订单退款
     *
     * @param OrderModel $order
     */
    protected function handleVipOrderRefund(OrderModel $order): void
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $itemInfo = $order->item_info;

        $diffTime = "-{$itemInfo['vip']['expiry']} months";
        $baseTime = $itemInfo['vip']['expiry_time'];

        $user->vip_expiry_time = strtotime($diffTime, $baseTime);

        if ($user->vip_expiry_time < time()) {
            $user->vip = 0;
        }

        if ($user->update() === false) {
            throw new \RuntimeException('Update User Vip Failed');
        }
    }

    /**
     * @param RefundModel $refund
     */
    protected function handleRefundFinishNotice(RefundModel $refund): void
    {
        $notice = new RefundFinishNotice();

        $notice->createTask($refund);
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|TaskModel[]
     */
    protected function findTasks(int $limit = 30)
    {
        $itemType = TaskModel::TYPE_REFUND;
        $status = TaskModel::STATUS_PENDING;
        $createTime = strtotime('-3 days');

        return TaskModel::query()
            ->where('item_type = :item_type:', ['item_type' => $itemType])
            ->andWhere('status = :status:', ['status' => $status])
            ->andWhere('create_time > :create_time:', ['create_time' => $createTime])
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

}
