<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Services;

use App\Caches\IndexFeaturedCourseList;
use App\Caches\IndexLatestCourseList;
use App\Caches\IndexPopularCourseList;
use App\Caches\IndexSlideList;

class Index extends Service
{

    public function getSlides(): array
    {
        $cache = new IndexSlideList();

        /**
         * @var array $slides
         */
        $slides = $cache->get();

        return $slides;
    }

    public function getPopularCourses(): array
    {
        $cache = new IndexPopularCourseList();

        return $cache->get();
    }

    public function getFeaturedCourses(): array
    {
        $cache = new IndexFeaturedCourseList();

        return $cache->get();
    }

    public function getLatestCourses(): array
    {
        $cache = new IndexLatestCourseList();

        return $cache->get();
    }

}
