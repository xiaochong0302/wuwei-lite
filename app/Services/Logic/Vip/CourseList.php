<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Vip;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\Service as LogicService;
use Phalcon\Paginator\RepositoryInterface;

class CourseList extends LogicService
{

    public function handle(string $type): RepositoryInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['published'] = 1;
        $params['deleted'] = 0;

        $sort = $type == 'discount' ? 'vip_discount' : 'vip_free';
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseRepo = new CourseRepo();

        $pager = $courseRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    protected function handleCourses(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $courses = $pager->getItems()->toArray();

        $baseUrl = kg_cos_url();

        $items = [];

        foreach ($courses as $course) {

            $course['cover'] = $baseUrl . $course['cover'];

            $items[] = [
                'id' => $course['id'],
                'title' => $course['title'],
                'slug' => $course['slug'],
                'cover' => $course['cover'],
                'regular_price' => (float)$course['regular_price'],
                'vip_price' => (float)$course['vip_price'],
                'rating' => (float)$course['rating'],
                'model' => $course['model'],
                'level' => $course['level'],
                'user_count' => $course['user_count'],
                'lesson_count' => $course['lesson_count'],
            ];
        }

        $pager->setItems($items);

        return $pager;
    }

}
