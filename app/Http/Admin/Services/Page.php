<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Page as PageModel;
use App\Repos\Page as PageRepo;
use App\Validators\Page as PageValidator;
use Phalcon\Paginator\RepositoryInterface;

class Page extends Service
{

    public function getPages(): RepositoryInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $pageRepo = new PageRepo();

        return $pageRepo->paginate($params, $sort, $page, $limit);
    }

    public function getPage(int $id): PageModel
    {
        return $this->findOrFail($id);
    }

    public function createPage(): PageModel
    {
        $post = $this->request->getPost();

        $validator = new PageValidator();

        $data = [];

        $data['title'] = $validator->checkTitle($post['title']);

        $page = new PageModel();

        $page->assign($data);

        $page->create();

        return $page;
    }

    public function updatePage(int $id): PageModel
    {
        $page = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new PageValidator();

        $data = [];

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['summary']);
        }

        if (isset($post['keywords'])) {
            $data['keywords'] = $validator->checkKeywords($post['keywords']);
        }

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $page->assign($data);

        $page->update();

        return $page;
    }

    public function deletePage(int $id): PageModel
    {
        $page = $this->findOrFail($id);

        $page->deleted = 1;

        $page->update();

        return $page;
    }

    public function restorePage(int $id): PageModel
    {
        $page = $this->findOrFail($id);

        $page->deleted = 0;

        $page->update();

        return $page;
    }

    protected function findOrFail(int $id): PageModel
    {
        $validator = new PageValidator();

        return $validator->checkPage($id);
    }

}
