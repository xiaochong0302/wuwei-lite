<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Notice;

use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Repos\Refund as RefundRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Notice\Email\RefundFinish as RefundFinishEmail;
use App\Services\Logic\Service as LogicService;

class RefundFinish extends LogicService
{

    public function createTask(RefundModel $refund): void
    {
        if (!$this->emailNoticeEnabled()) return;

        $task = new TaskModel();

        $task->item_id = $refund->id;
        $task->item_type = TaskModel::TYPE_NOTICE_REFUND_FINISH;
        $task->priority = TaskModel::PRIORITY_MIDDLE;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

    public function handleTask(TaskModel $task): bool
    {
        if (!$this->emailNoticeEnabled()) return true;

        $refundRepo = new RefundRepo();

        $refund = $refundRepo->findById($task->item_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($refund->owner_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'refund' => [
                'sn' => $refund->sn,
                'subject' => $refund->subject,
                'amount' => $refund->amount,
                'currency' => $refund->currency,
            ],
        ];

        $mail = new RefundFinishEmail();

        return $mail->handle($params);
    }

    protected function emailNoticeEnabled(): bool
    {
        $notification = kg_setting('mail', 'notification');

        $settings = json_decode($notification, true);

        return $settings['refund_finish'] == 1;
    }

}
