<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Search;

use App\Library\Paginator\Adapter\XunSearch as XunSearchPaginator;
use App\Library\Paginator\Query as PagerQuery;
use App\Services\Search\ChapterSearcher as LessonSearcherService;
use Phalcon\Paginator\RepositoryInterface;
use Phalcon\Support\HelperFactory;

class Chapter extends Handler
{

    public function search(): RepositoryInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $searcher = new LessonSearcherService();

        $paginator = new XunSearchPaginator([
            'xs' => $searcher->getXS(),
            'highlight' => $searcher->getHighlightFields(),
            'query' => $this->handleKeywords($params['query']),
            'page' => $page,
            'limit' => $limit,
        ]);

        $pager = $paginator->paginate();

        return $this->handleChapters($pager);
    }

    public function getHotQuery(int $limit = 10, string $type = 'total'): array
    {
        $searcher = new LessonSearcherService();

        return $searcher->getHotQuery($limit, $type);
    }

    public function getRelatedQuery(string $query, int $limit = 10): array
    {
        $searcher = new LessonSearcherService();

        return $searcher->getRelatedQuery($query, $limit);
    }

    protected function handleChapters(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $items = [];

        $baseUrl = kg_cos_url();

        $helper = new HelperFactory();

        foreach ($pager->getItems() as $item) {

            $course = json_decode($item['course'], true);

            if (!empty($course['cover']) && !$helper->startsWith($course['cover'], 'http')) {
                $course['cover'] = $baseUrl . $course['cover'];
            }

            $items[] = [
                'id' => (int)$item['id'],
                'title' => (string)$item['title'],
                'slug' => (string)$item['slug'],
                'summary' => (string)$item['summary'],
                'model' => (int)$item['model'],
                'free' => (int)$item['free'],
                'create_time' => (int)$item['create_time'],
                'update_time' => (int)$item['update_time'],
                'user_count' => (int)$item['user_count'],
                'like_count' => (int)$item['like_count'],
                'comment_count' => (int)$item['comment_count'],
                'course' => $course,
            ];
        }

        $pager->setItems($items);

        return $pager;
    }

}
