<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Listeners;

use App\Models\Comment as CommentModel;
use Phalcon\Events\Event as PhEvent;

class Comment extends Listener
{

    public function afterCreate(PhEvent $event, $source, CommentModel $comment): void
    {

    }

    public function afterUpdate(PhEvent $event, $source, CommentModel $comment): void
    {

    }

    public function afterDelete(PhEvent $event, $source, CommentModel $comment): void
    {

    }

    public function afterRestore(PhEvent $event, $source, CommentModel $comment): void
    {

    }

    public function afterReply(PhEvent $event, $source, CommentModel $reply): void
    {

    }

    public function afterLike(PhEvent $event, $source, CommentModel $comment): void
    {

    }

    public function afterUndoLike(PhEvent $event, $source, CommentModel $comment): void
    {

    }

}
