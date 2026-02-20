<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Listeners;

use App\Models\Chapter as ChapterModel;
use Phalcon\Events\Event as PhEvent;

class Chapter extends Listener
{

    public function afterCreate(PhEvent $event, object $source, ChapterModel $chapter): void
    {

    }

    public function afterUpdate(PhEvent $event, object $source, ChapterModel $chapter): void
    {

    }

    public function afterDelete(PhEvent $event, object $source, ChapterModel $chapter): void
    {

    }

    public function afterRestore(PhEvent $event, object $source, ChapterModel $chapter): void
    {

    }

    public function afterView(PhEvent $event, object $source, ChapterModel $chapter): void
    {

    }

    public function afterLike(PhEvent $event, object $source, ChapterModel $chapter): void
    {

    }

    public function afterUndoLike(PhEvent $event, object $source, ChapterModel $chapter): void
    {

    }

}
