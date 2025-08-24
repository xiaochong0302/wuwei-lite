<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Caches\IndexSlideList as IndexSlideListCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Slide as SlideModel;
use App\Repos\Slide as SlideRepo;
use App\Validators\Slide as SlideValidator;
use Phalcon\Paginator\RepositoryInterface;

class Slide extends Service
{

    public function getSlides(): RepositoryInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $slideRepo = new SlideRepo();

        return $slideRepo->paginate($params, $sort, $page, $limit);
    }

    public function getSlide(int $id): SlideModel
    {
        return $this->findOrFail($id);
    }

    public function createSlide(): SlideModel
    {
        $post = $this->request->getPost();

        $validator = new SlideValidator();

        $slide = new SlideModel();

        $slide->title = $validator->checkTitle($post['title']);

        $slide->create();

        return $slide;
    }

    public function updateSlide(int $id): SlideModel
    {
        $slide = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new SlideValidator();

        $data = [];

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['link'])) {
            $data['link'] = $validator->checkLink($post['link']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['summary']);
        }

        if (isset($post['priority'])) {
            $data['priority'] = $validator->checkPriority($post['priority']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $slide->assign($data);

        $slide->update();

        $this->rebuildIndexSlideListCache();

        return $slide;
    }

    public function deleteSlide(int $id): SlideModel
    {
        $slide = $this->findOrFail($id);

        $slide->deleted = 1;

        $slide->update();

        $this->rebuildIndexSlideListCache();

        return $slide;
    }

    public function restoreSlide(int $id): SlideModel
    {
        $slide = $this->findOrFail($id);

        $slide->deleted = 0;

        $slide->update();

        $this->rebuildIndexSlideListCache();

        return $slide;
    }

    protected function findOrFail(int $id): SlideModel
    {
        $validator = new SlideValidator();

        return $validator->checkSlide($id);
    }

    protected function rebuildIndexSlideListCache(): void
    {
        $cache = new IndexSlideListCache();

        $cache->rebuild();
    }

}
