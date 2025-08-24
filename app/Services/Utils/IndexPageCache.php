<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Utils;

use App\Caches\IndexFeaturedCourseList as IndexFeaturedCourseListCache;
use App\Caches\IndexLatestCourseList as IndexNewCourseListCache;
use App\Caches\IndexPopularCourseList as IndexPopularCourseListCache;
use App\Caches\IndexSlideList as IndexSlideListCache;
use App\Services\Service as AppService;

class IndexPageCache extends AppService
{

    public function rebuild(string $section = 'all'): void
    {
        if ($section == 'all' || $section == 'slide') {
            $cache = new IndexSlideListCache();
            $cache->rebuild();
        }

        if ($section == 'all' || $section == 'featured_course') {
            $cache = new IndexFeaturedCourseListCache();
            $cache->rebuild();
        }

        if ($section == 'all' || $section == 'popular_course') {
            $cache = new IndexPopularCourseListCache();
            $cache->rebuild();
        }

        if ($section == 'all' || $section == 'new_course') {
            $cache = new IndexNewCourseListCache();
            $cache->rebuild();
        }
    }

}
