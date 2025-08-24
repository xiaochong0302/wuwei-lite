<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\Task as TaskModel;
use App\Services\Logic\Notice\AccountLogin as AccountLoginNotice;
use App\Services\Logic\Notice\OrderFinish as OrderFinishNotice;
use App\Services\Logic\Notice\RefundFinish as RefundFinishNotice;
use App\Services\Logic\Notice\ReviewRemind as ReviewRemindNotice;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class NoticeTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $tasks = $this->findTasks(300);

        if ($tasks->count() == 0) return;

        foreach ($tasks as $task) {

            if ($task->try_count > $task->max_try_count) {
                $task->status = TaskModel::STATUS_FAILED;
                $task->update();
                continue;
            }

            $task->locked = 1;

            $result = false;

            try {

                switch ($task->item_type) {
                    case TaskModel::TYPE_NOTICE_ACCOUNT_LOGIN:
                        $result = $this->handleAccountLoginNotice($task);
                        break;
                    case TaskModel::TYPE_NOTICE_ORDER_FINISH:
                        $result = $this->handleOrderFinishNotice($task);
                        break;
                    case TaskModel::TYPE_NOTICE_REFUND_FINISH:
                        $result = $this->handleRefundFinishNotice($task);
                        break;
                    case TaskModel::TYPE_NOTICE_REVIEW_REMIND:
                        $result = $this->handleReviewRemindNotice($task);
                        break;
                }

            } catch (\Exception $e) {

                $logger = $this->getLogger('notice');

                $logger->error('Notice Process Exception: ' . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }

            $task->try_count += 1;

            if ($result) {
                $task->status = TaskModel::STATUS_FINISHED;
            } else {
                $task->locked = 0;
                $task->priority += 1;
            }

            $task->update();
        }

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function handleAccountLoginNotice(TaskModel $task): bool
    {
        $notice = new AccountLoginNotice();

        return $notice->handleTask($task);
    }

    protected function handleOrderFinishNotice(TaskModel $task): bool
    {
        $notice = new OrderFinishNotice();

        return $notice->handleTask($task);
    }

    protected function handleRefundFinishNotice(TaskModel $task): bool
    {
        $notice = new RefundFinishNotice();

        return $notice->handleTask($task);
    }

    protected function handleReviewRemindNotice(TaskModel $task): bool
    {
        $notice = new ReviewRemindNotice();

        return $notice->handleTask($task);
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|TaskModel[]
     */
    protected function findTasks(int $limit = 300)
    {
        $itemTypes = [
            TaskModel::TYPE_NOTICE_ACCOUNT_LOGIN,
            TaskModel::TYPE_NOTICE_ORDER_FINISH,
            TaskModel::TYPE_NOTICE_REFUND_FINISH,
            TaskModel::TYPE_NOTICE_REVIEW_REMIND,
        ];

        $status = TaskModel::STATUS_PENDING;

        $createTime = strtotime('-1 days');

        return TaskModel::query()
            ->inWhere('item_type', $itemTypes)
            ->andWhere('status = :status:', ['status' => $status])
            ->andWhere('locked = :locked:', ['locked' => 0])
            ->andWhere('create_time > :create_time:', ['create_time' => $createTime])
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

}
