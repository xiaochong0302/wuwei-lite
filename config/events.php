<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

use App\Listeners\Account;
use App\Listeners\Chapter;
use App\Listeners\Comment;
use App\Listeners\Course;
use App\Listeners\Order;
use App\Listeners\Page;
use App\Listeners\Review;
use App\Listeners\Site;
use App\Listeners\UserDailyCounter;

return [
    'Account' => Account::class,
    'Chapter' => Chapter::class,
    'Course' => Course::class,
    'Comment' => Comment::class,
    'Page' => Page::class,
    'Review' => Review::class,
    'Site' => Site::class,
    'Order' => Order::class,
    'UserDailyCounter' => UserDailyCounter::class,
];
