<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Listeners;

use App\Models\Page as PageModel;
use Phalcon\Events\Event as PhEvent;

class Page extends Listener
{

    public function afterCreate(PhEvent $event, $source, PageModel $page): void
    {

    }

    public function afterUpdate(PhEvent $event, $source, PageModel $page): void
    {

    }

    public function afterDelete(PhEvent $event, $source, PageModel $page): void
    {

    }

    public function afterRestore(PhEvent $event, $source, PageModel $page): void
    {

    }

    public function afterView(PhEvent $event, $source, PageModel $page): void
    {

    }

}
