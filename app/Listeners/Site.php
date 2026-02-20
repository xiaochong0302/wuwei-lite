<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Listeners;

use App\Models\User as UserModel;
use Phalcon\Events\Event as PhEvent;

class Site extends Listener
{

    public function afterView(PhEvent $event, object $source, ?UserModel $user): void
    {

    }

}
