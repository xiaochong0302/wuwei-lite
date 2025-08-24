<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Notice;

use App\Models\Order as OrderModel;
use App\Models\Task as TaskModel;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Notice\Email\OrderFinish as OrderFinishEmail;
use App\Services\Logic\Service as LogicService;

class OrderFinish extends LogicService
{

    public function createTask(OrderModel $order): void
    {
        if (!$this->emailNoticeEnabled()) return;

        $task = new TaskModel();

        $task->item_id = $order->id;
        $task->item_type = TaskModel::TYPE_NOTICE_ORDER_FINISH;
        $task->priority = TaskModel::PRIORITY_HIGH;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

    public function handleTask(TaskModel $task): bool
    {
        if (!$this->emailNoticeEnabled()) return true;

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($task->item_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'order' => [
                'sn' => $order->sn,
                'subject' => $order->subject,
                'amount' => $order->amount,
                'currency' => $order->currency,
            ],
        ];

        $mail = new OrderFinishEmail();

        return $mail->handle($params);
    }

    protected function emailNoticeEnabled(): bool
    {
        $notification = kg_setting('mail', 'notification');

        $settings = json_decode($notification, true);

        return $settings['order_finish'] == 1;
    }

}
