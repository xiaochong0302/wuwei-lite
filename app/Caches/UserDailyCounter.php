<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

class UserDailyCounter extends Counter
{

    public function getLifetime(): int
    {
        $tomorrow = strtotime('tomorrow');

        return $tomorrow - time();
    }

    public function getKey($id = null): string
    {
        return "user-daily-counter-{$id}";
    }

    public function getContent($id = null): array
    {
        return [
            'order_count' => 0,
            'comment_count' => 0,
            'chapter_like_count' => 0,
            'comment_like_count' => 0,
            'review_like_count' => 0,
        ];
    }

}
