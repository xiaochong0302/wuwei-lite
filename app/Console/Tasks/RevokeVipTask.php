<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\User as UserModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class RevokeVipTask extends Task
{

    public function mainAction()
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $users = $this->findUsers();

        echo sprintf('pending users: %s', $users->count()) . PHP_EOL;

        if ($users->count() == 0) return;

        echo '------ start revoke vip task ------' . PHP_EOL;

        foreach ($users as $user) {
            $user->vip = 0;
            $user->update();
        }

        echo '------ end revoke vip task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    /**
     * 查找待撤销会员
     *
     * @param int $limit
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    protected function findUsers($limit = 1000)
    {
        $time = time();

        return UserModel::query()
            ->where('vip = 1')
            ->andWhere('vip_expiry_time < :time:', ['time' => $time])
            ->limit($limit)
            ->execute();
    }

}
