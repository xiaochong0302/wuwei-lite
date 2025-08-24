<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Repos\Comment as CommentRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Package as PackageRepo;
use App\Repos\Review as ReviewRepo;
use App\Repos\User as UserRepo;

class SiteGlobalStat extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 15 * 60;

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getKey($id = null): string
    {
        return 'site-global-stat';
    }

    public function getContent($id = null): array
    {
        $courseRepo = new CourseRepo();
        $commentRepo = new CommentRepo();
        $packageRepo = new PackageRepo();
        $reviewRepo = new ReviewRepo();
        $userRepo = new UserRepo();
        $orderRepo = new OrderRepo();

        $list = [
            'course_count' => $courseRepo->countCourses(),
            'package_count' => $packageRepo->countPackages(),
            'review_count' => $reviewRepo->countReviews(),
            'comment_count' => $commentRepo->countComments(),
            'vip_count' => $userRepo->countVipUsers(),
            'user_count' => $userRepo->countUsers(),
            'order_count' => $orderRepo->countOrders(),
        ];

        return array_map(function ($item) {
            return number_format($item);
        }, $list);
    }

}
