<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Listeners;

use App\Models\User as UserModel;
use App\Services\Logic\Notice\AccountLogin as AccountLoginNotice;
use Phalcon\Events\Event as PhEvent;

class Account extends Listener
{

    public function afterRegister(PhEvent $event, $source, UserModel $user): void
    {

    }

    public function afterLogin(PhEvent $event, $source, UserModel $user): void
    {
        $this->handleLoginNotice($user);
    }

    public function afterLogout(PhEvent $event, $source, UserModel $user): void
    {

    }

    protected function handleLoginNotice(UserModel $user)
    {
        $notice = new AccountLoginNotice();

        $notice->createTask($user);
    }

}
