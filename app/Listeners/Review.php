<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Listeners;

use App\Models\Review as ReviewModel;
use Phalcon\Events\Event as PhEvent;

class Review extends Listener
{

    public function afterCreate(PhEvent $event, $source, ReviewModel $review): void
    {

    }

    public function afterUpdate(PhEvent $event, $source, ReviewModel $review): void
    {

    }

    public function afterDelete(PhEvent $event, $source, ReviewModel $review): void
    {

    }

    public function afterRestore(PhEvent $event, $source, ReviewModel $review): void
    {

    }

    public function afterApprove(PhEvent $event, $source, ReviewModel $review): void
    {

    }

    public function afterReject(PhEvent $event, $source, ReviewModel $review): void
    {

    }

    public function afterLike(PhEvent $event, $source, ReviewModel $review): void
    {

    }

    public function afterUndoLike(PhEvent $event, $source, ReviewModel $review): void
    {

    }

}
