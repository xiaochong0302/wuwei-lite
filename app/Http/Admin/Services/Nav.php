<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Caches\NavTreeList as NavTreeListCache;
use App\Models\Nav as NavModel;
use App\Repos\Nav as NavRepo;
use App\Validators\Nav as NavValidator;

class Nav extends Service
{

    public function getNav(int $id): NavModel
    {
        return $this->findOrFail($id);
    }

    public function getParentNav(int $id): NavModel
    {
        if ($id > 0) {
            $parent = NavModel::findFirst($id);
        } else {
            $parent = new NavModel();
            $parent->id = 0;
            $parent->level = 0;
        }

        return $parent;
    }

    public function getTopNavs(): array
    {
        $navRepo = new NavRepo();

        return $navRepo->findAll([
            'position' => NavModel::POS_TOP,
            'parent_id' => 0,
            'deleted' => 0,
        ])->toArray();
    }

    public function getChildNavs(int $parentId): array
    {
        $navRepo = new NavRepo();

        return $navRepo->findAll([
            'parent_id' => $parentId,
            'deleted' => 0,
        ])->toArray();
    }

    public function createNav(): NavModel
    {
        $post = $this->request->getPost();

        $validator = new NavValidator();

        $data = [
            'parent_id' => 0,
            'published' => 1,
        ];

        $parent = null;

        if ($post['parent_id'] > 0) {
            $parent = $validator->checkParent($post['parent_id']);
            $data['parent_id'] = $parent->id;
        }

        $data['name'] = $validator->checkName($post['name']);
        $data['priority'] = $validator->checkPriority($post['priority']);
        $data['url'] = $validator->checkUrl($post['url']);
        $data['target'] = $validator->checkTarget($post['target']);
        $data['position'] = $validator->checkPosition($post['position']);

        $nav = new NavModel();

        $nav->assign($data);

        $nav->create();

        if ($parent) {
            $nav->path = $parent->path . $nav->id . ',';
            $nav->level = $parent->level + 1;
            $nav->position = $parent->position;
        } else {
            $nav->path = ',' . $nav->id . ',';
            $nav->level = 1;
        }

        $nav->update();

        $this->updateNavStats($nav);

        $this->rebuildNavCache();

        return $nav;
    }

    public function updateNav(int $id): NavModel
    {
        $nav = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new NavValidator();

        $data = [];

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
        }

        if (isset($post['position'])) {
            $data['position'] = $validator->checkPosition($post['position']);
        }

        if (isset($post['url'])) {
            $data['url'] = $validator->checkUrl($post['url']);
        }

        if (isset($post['target'])) {
            $data['target'] = $validator->checkTarget($post['target']);
        }

        if (isset($post['priority'])) {
            $data['priority'] = $validator->checkPriority($post['priority']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
            if ($nav->parent_id == 0) {
                if ($nav->published == 0 && $post['published'] == 1) {
                    $this->enableChildNavs($nav->id);
                } elseif ($nav->published == 1 && $post['published'] == 0) {
                    $this->disableChildNavs($nav->id);
                }
            }
        }

        if ($nav->parent_id > 0) {
            $parent = $this->findOrFail($nav->parent_id);
            $data['position'] = $parent->position;
        }

        $nav->assign($data);

        $nav->update();

        $this->updateNavStats($nav);

        $this->rebuildNavCache();

        return $nav;
    }

    public function deleteNav(int $id): NavModel
    {
        $nav = $this->findOrFail($id);

        $validator = new NavValidator();

        $validator->checkDeleteAbility($nav);

        $nav->deleted = 1;

        $nav->update();

        $this->updateNavStats($nav);

        $this->rebuildNavCache();

        return $nav;
    }

    public function restoreNav(int $id): NavModel
    {
        $nav = $this->findOrFail($id);

        $nav->deleted = 0;

        $nav->update();

        $this->updateNavStats($nav);

        $this->rebuildNavCache();

        return $nav;
    }

    protected function updateNavStats(NavModel $nav): void
    {
        $navRepo = new NavRepo();

        if ($nav->parent_id > 0) {
            $nav = $navRepo->findById($nav->parent_id);
        }

        $childCount = $navRepo->countChildNavs($nav->id);

        $nav->child_count = $childCount;

        $nav->update();
    }

    protected function rebuildNavCache(): void
    {
        $cache = new NavTreeListCache();

        $cache->rebuild();
    }

    protected function enableChildNavs(int $parentId): void
    {
        $navRepo = new NavRepo();

        $navs = $navRepo->findAll(['parent_id' => $parentId]);

        if ($navs->count() == 0) {
            return;
        }

        foreach ($navs as $nav) {
            $nav->published = 1;
            $nav->update();
        }
    }

    protected function disableChildNavs(int $parentId): void
    {
        $navRepo = new NavRepo();

        $navs = $navRepo->findAll(['parent_id' => $parentId]);

        if ($navs->count() == 0) {
            return;
        }

        foreach ($navs as $nav) {
            $nav->published = 0;
            $nav->update();
        }
    }

    protected function findOrFail(int $id): NavModel
    {
        $validator = new NavValidator();

        return $validator->checkNav($id);
    }

}
