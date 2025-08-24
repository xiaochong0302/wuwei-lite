<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\User as UserModel;
use Phalcon\Mvc\Model\ResultsetInterface;

class UnlockUserTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $users = $this->findUsers();

        echo sprintf('pending users: %s', $users->count()) . PHP_EOL;

        if ($users->count() == 0) return;

        echo '------ start unlock user task ------' . PHP_EOL;

        foreach ($users as $user) {
            $user->locked = 0;
            $user->update();
        }

        echo '------ end unlock user task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    /**
     * 查找待解锁用户
     *
     * @param int $limit
     * @return ResultsetInterface|UserModel[]
     */
    protected function findUsers(int $limit = 1000)
    {
        $time = time() - 6 * 3600;

        return UserModel::query()
            ->where('locked = 1')
            ->andWhere('lock_expiry_time < :time:', ['time' => $time])
            ->limit($limit)
            ->execute();
    }

}
