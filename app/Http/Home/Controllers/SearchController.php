<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Search\Chapter as ChapterSearchService;
use App\Services\Logic\Search\Course as CourseSearchService;

/**
 * @RoutePrefix("/search")
 */
class SearchController extends Controller
{

    /**
     * @Get("/", name="home.search.index")
     */
    public function indexAction()
    {
        $query = $this->request->get('query', ['trim', 'string']);
        $type = $this->request->get('type', ['trim', 'string'], 'course');

        if (strlen($query) == 0) {
            return $this->response->redirect(['for' => 'home.course.list']);
        }

        $title = $this->locale->query('page_search_x', ['x' => $query]);

        $this->seo->prependTitle($title);

        $service = $this->getSearchService($type);

        $hotQueries = $service->getHotQuery();

        $relatedQueries = $service->getRelatedQuery($query);

        $pager = $service->search();

        $this->view->setVar('hot_queries', $hotQueries);
        $this->view->setVar('related_queries', $relatedQueries);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @param string $type
     * @return CourseSearchService
     */
    protected function getSearchService($type = 'course')
    {
        return match ($type) {
            'chapter' => new ChapterSearchService(),
            default => new CourseSearchService(),
        };
    }

}
