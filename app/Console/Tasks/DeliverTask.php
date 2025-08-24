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
use App\Repos\Course as CourseRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Package as PackageRepo;
use App\Repos\User as UserRepo;
use App\Repos\Vip as VipRepo;
use App\Services\Logic\Deliver\CourseDeliver as CourseDeliverService;
use App\Services\Logic\Deliver\PackageDeliver as PackageDeliverService;
use App\Services\Logic\Deliver\VipDeliver as VipDeliverService;
use App\Services\Logic\Notice\OrderFinish as OrderFinishNotice;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class DeliverTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $logger = $this->getLogger('deliver');

        $logger->debug('delivering...');

        $tasks = $this->findTasks(30);

        echo sprintf('pending tasks: %s', $tasks->count()) . PHP_EOL;

        if ($tasks->count() == 0) return;

        echo '------ start deliver task ------' . PHP_EOL;

        $orderRepo = new OrderRepo();

        foreach ($tasks as $task) {

            $order = $orderRepo->findById($task->item_id);

            try {

                $this->db->begin();

                switch ($order->item_type) {
                    case KgSaleModel::ITEM_COURSE:
                        $this->handleCourseOrder($order);
                        break;
                    case KgSaleModel::ITEM_PACKAGE:
                        $this->handlePackageOrder($order);
                        break;
                    case KgSaleModel::ITEM_VIP:
                        $this->handleVipOrder($order);
                        break;
                }

                $order->status = OrderModel::STATUS_FINISHED;
                $order->update();

                $task->status = TaskModel::STATUS_FINISHED;
                $task->update();

                $this->db->commit();

                $logger->info("order:{$order->id} delivered");

            } catch (\Exception $e) {

                $this->db->rollback();

                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count > $task->max_try_count) {
                    $task->status = TaskModel::STATUS_FAILED;
                }

                $task->update();

                $logger->error('Deliver Task Exception ' . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }

            if ($task->status == TaskModel::STATUS_FINISHED) {
                $this->handleOrderFinishNotice($order);
            } elseif ($task->status == TaskModel::STATUS_FAILED) {
                $this->handleOrderRefund($order);
            }
        }

        echo '------ end deliver task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function handleCourseOrder(OrderModel $order): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($order->item_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $service = new CourseDeliverService();

        $service->handle($course, $user);
    }

    protected function handlePackageOrder(OrderModel $order): void
    {
        $packageRepo = new PackageRepo();

        $package = $packageRepo->findById($order->item_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $service = new PackageDeliverService();

        $service->handle($package, $user);
    }

    protected function handleVipOrder(OrderModel $order): void
    {
        $vipRepo = new VipRepo();

        $vip = $vipRepo->findById($order->item_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $service = new VipDeliverService();

        $service->handle($vip, $user);
    }

    protected function handleOrderFinishNotice(OrderModel $order): void
    {
        $notice = new OrderFinishNotice();

        $notice->createTask($order);
    }

    protected function handleOrderRefund(OrderModel $order): void
    {
        $refund = new RefundModel();

        $refund->owner_id = $order->owner_id;
        $refund->order_id = $order->id;
        $refund->subject = $order->subject;
        $refund->amount = $order->amount;
        $refund->apply_note = 'automatic refund';
        $refund->review_note = 'automatic refund';

        $refund->create();

        $task = new TaskModel();

        $task->item_id = $refund->id;
        $task->item_type = TaskModel::TYPE_REFUND;
        $task->priority = TaskModel::PRIORITY_HIGH;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|TaskModel[]
     */
    protected function findTasks(int $limit = 100)
    {
        $itemType = TaskModel::TYPE_DELIVER;
        $status = TaskModel::STATUS_PENDING;
        $createTime = strtotime('-7 days');

        return TaskModel::query()
            ->where('item_type = :item_type:', ['item_type' => $itemType])
            ->andWhere('status = :status:', ['status' => $status])
            ->andWhere('create_time > :create_time:', ['create_time' => $createTime])
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

}
