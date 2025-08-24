<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Caches\UserDailyCounter as CacheUserDailyCounter;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\User as UserModel;

class UserLimit extends Validator
{

    protected CacheUserDailyCounter $counter;

    public function __construct()
    {
        $this->counter = new CacheUserDailyCounter();
    }

    public function checkDailyOrderLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'order_count');

        if ($count > 50) {
            throw new BadRequestException('user_limit.reach_daily_order_limit');
        }
    }

    public function checkDailyCommentLimit(UserModel $user): void
    {
        $count = $this->counter->hGet($user->id, 'comment_count');

        $limit = 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_comment_limit');
        }
    }

    public function checkDailyChapterLikeLimit(UserModel $user): void
    {
        $count = $this->counter->hGet($user->id, 'chapter_like_count');

        $limit = 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyReviewLikeLimit(UserModel $user): void
    {
        $count = $this->counter->hGet($user->id, 'review_like_count');

        $limit = 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyCommentLikeLimit(UserModel $user): void
    {
        $count = $this->counter->hGet($user->id, 'comment_like_count');

        $limit = 100;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

}
