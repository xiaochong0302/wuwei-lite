<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Notice;

use App\Models\Task as TaskModel;
use App\Models\User as UserModel;
use App\Services\Logic\Notice\Email\AccountLogin as AccountLoginEmail;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;

class AccountLogin extends LogicService
{

    use ClientTrait;

    public function createTask(UserModel $user): void
    {
        if (!$this->emailNoticeEnabled()) return;

        $task = new TaskModel();

        $loginIp = $this->getClientIp();

        $itemInfo = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'login' => [
                'ip' => $loginIp,
                'time' => date('Y-m-d H:i:s'),
            ],
        ];

        $task->item_id = $user->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_ACCOUNT_LOGIN;
        $task->priority = TaskModel::PRIORITY_LOW;
        $task->status = TaskModel::STATUS_PENDING;
        $task->max_try_count = 1;

        $task->create();
    }

    public function handleTask(TaskModel $task): bool
    {
        if (!$this->emailNoticeEnabled()) return true;

        $mail = new AccountLoginEmail();

        $params = $task->item_info;

        return $mail->handle($params);
    }

    protected function emailNoticeEnabled(): bool
    {
        $notification = kg_setting('mail', 'notification');

        $settings = json_decode($notification, true);

        return $settings['account_login'] == 1;
    }

}
