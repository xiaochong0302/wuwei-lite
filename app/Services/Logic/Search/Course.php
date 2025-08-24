<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Search;

use App\Library\Paginator\Adapter\XunSearch as XunSearchPaginator;
use App\Library\Paginator\Query as PagerQuery;
use App\Services\Search\CourseSearcher as CourseSearcherService;
use Phalcon\Paginator\RepositoryInterface;
use Phalcon\Support\HelperFactory;

class Course extends Handler
{

    public function search(): RepositoryInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $searcher = new CourseSearcherService();

        $paginator = new XunSearchPaginator([
            'xs' => $searcher->getXS(),
            'highlight' => $searcher->getHighlightFields(),
            'query' => $this->handleKeywords($params['query']),
            'page' => $page,
            'limit' => $limit,
        ]);

        $pager = $paginator->paginate();

        return $this->handleCourses($pager);
    }

    public function getHotQuery(int $limit = 10, string $type = 'total'): array
    {
        $searcher = new CourseSearcherService();

        return $searcher->getHotQuery($limit, $type);
    }

    public function getRelatedQuery(string $query, int $limit = 10): array
    {
        $searcher = new CourseSearcherService();

        return $searcher->getRelatedQuery($query, $limit);
    }

    protected function handleCourses(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $items = [];

        $baseUrl = kg_cos_url();

        $helper = new HelperFactory();

        foreach ($pager->getItems() as $item) {

            $category = json_decode($item['category'], true);
            $teacher = json_decode($item['teacher'], true);

            if (!empty($item['cover']) && !$helper->startsWith($item['cover'], 'http')) {
                $item['cover'] = $baseUrl . $item['cover'];
            }

            $items[] = [
                'id' => (int)$item['id'],
                'title' => (string)$item['title'],
                'slug' => (string)$item['slug'],
                'cover' => (string)$item['cover'],
                'summary' => (string)$item['summary'],
                'level' => (int)$item['level'],
                'rating' => round($item['rating'], 1),
                'create_time' => (int)$item['create_time'],
                'update_time' => (int)$item['update_time'],
                'user_count' => (int)$item['user_count'],
                'lesson_count' => (int)$item['lesson_count'],
                'review_count' => (int)$item['review_count'],
                'favorite_count' => (int)$item['favorite_count'],
                'category' => $category,
                'teacher' => $teacher,
            ];
        }

        $pager->setItems($items);

        return $pager;
    }

}
