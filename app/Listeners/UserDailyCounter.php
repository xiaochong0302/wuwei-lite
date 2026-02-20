<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Listeners;

use App\Caches\UserDailyCounter as CacheUserDailyCounter;
use App\Models\User as UserModel;
use Phalcon\Events\Event as PhEvent;

class UserDailyCounter extends Listener
{

    /**
     * @var CacheUserDailyCounter
     */
    protected CacheUserDailyCounter $counter;

    public function __construct()
    {
        $this->counter = new CacheUserDailyCounter();
    }

    public function incrFavoriteCount(PhEvent $event, object $source, UserModel $user): void
    {
        $this->counter->hIncrBy($user->id, 'favorite_count');
    }

    public function incrCommentCount(PhEvent $event, object $source, UserModel $user): void
    {
        $this->counter->hIncrBy($user->id, 'comment_count');
    }

    public function incrReviewCount(PhEvent $event, object $source, UserModel $user): void
    {
        $this->counter->hIncrBy($user->id, 'review_count');
    }

    public function incrOrderCount(PhEvent $event, object $source, UserModel $user): void
    {
        $this->counter->hIncrBy($user->id, 'order_count');
    }

    public function incrChapterLikeCount(PhEvent $event, object $source, UserModel $user): void
    {
        $this->counter->hIncrBy($user->id, 'chapter_like_count');
    }

    public function incrReviewLikeCount(PhEvent $event, object $source, UserModel $user): void
    {
        $this->counter->hIncrBy($user->id, 'review_like_count');
    }

    public function incrCommentLikeCount(PhEvent $event, object $source, UserModel $user): void
    {
        $this->counter->hIncrBy($user->id, 'comment_like_count');
    }

}
