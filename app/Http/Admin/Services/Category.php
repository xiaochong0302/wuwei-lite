<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Caches\Category as CategoryCache;
use App\Caches\CategoryAllList as CategoryAllListCache;
use App\Caches\CategoryList as CategoryListCache;
use App\Caches\CategoryTreeList as CategoryTreeListCache;
use App\Models\Category as CategoryModel;
use App\Repos\Category as CategoryRepo;
use App\Validators\Category as CategoryValidator;

class Category extends Service
{

    public function getCategory(int $id): CategoryModel
    {
        return $this->findOrFail($id);
    }

    public function getParentCategory(int $id): CategoryModel
    {
        if ($id > 0) {
            $parent = CategoryModel::findFirst($id);
        } else {
            $parent = new CategoryModel();
            $parent->id = 0;
            $parent->level = 0;
        }

        return $parent;
    }

    public function getChildCategories(int $parentId): array
    {
        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $categoryRepo = new CategoryRepo();

        return $categoryRepo->findAll([
            'parent_id' => $parentId,
            'deleted' => $deleted,
        ])->toArray();
    }

    public function getTopCategories(): array
    {
        $categoryRepo = new CategoryRepo();

        return $categoryRepo->findAll([
            'parent_id' => 0,
            'deleted' => 0,
        ])->toArray();
    }

    public function createCategory(): CategoryModel
    {
        $post = $this->request->getPost();

        $validator = new CategoryValidator();

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

        $category = new CategoryModel();

        $category->assign($data);

        $category->create();

        if ($parent) {
            $category->path = $parent->path . $category->id . ',';
            $category->level = $parent->level + 1;
        } else {
            $category->path = ',' . $category->id . ',';
            $category->level = 1;
        }

        $category->update();

        $this->updateCategoryStats($category);
        $this->rebuildCategoryCache($category);

        return $category;
    }

    public function updateCategory(int $id): CategoryModel
    {
        $category = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new CategoryValidator();

        $data = [];

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
        }

        if (isset($post['priority'])) {
            $data['priority'] = $validator->checkPriority($post['priority']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
            if ($category->parent_id == 0) {
                if ($category->published == 0 && $post['published'] == 1) {
                    $this->enableChildCategories($category->id);
                } elseif ($category->published == 1 && $post['published'] == 0) {
                    $this->disableChildCategories($category->id);
                }
            }
        }

        $category->assign($data);

        $category->update();

        $this->updateCategoryStats($category);
        $this->rebuildCategoryCache($category);

        return $category;
    }

    public function deleteCategory(int $id): CategoryModel
    {
        $category = $this->findOrFail($id);

        $validator = new CategoryValidator();

        $validator->checkDeleteAbility($category);

        $category->deleted = 1;

        $category->update();

        $this->updateCategoryStats($category);
        $this->rebuildCategoryCache($category);

        return $category;
    }

    public function restoreCategory(int $id): CategoryModel
    {
        $category = $this->findOrFail($id);

        $category->deleted = 0;

        $category->update();

        $this->updateCategoryStats($category);
        $this->rebuildCategoryCache($category);

        return $category;
    }

    protected function findOrFail(int $id): CategoryModel
    {
        $validator = new CategoryValidator();

        return $validator->checkCategory($id);
    }

    protected function updateCategoryStats(CategoryModel $category): CategoryModel
    {
        $categoryRepo = new CategoryRepo();

        if ($category->parent_id > 0) {
            $category = $categoryRepo->findById($category->parent_id);
        }

        $childCount = $categoryRepo->countChildCategories($category->id);

        $category->child_count = $childCount;

        $category->update();

        return $category;
    }

    protected function rebuildCategoryCache(CategoryModel $category): void
    {
        $cache = new CategoryCache();

        $cache->rebuild($category->id);

        $cache = new CategoryListCache();

        $cache->rebuild();

        $cache = new CategoryTreeListCache();

        $cache->rebuild();

        $cache = new CategoryAllListCache();

        $cache->rebuild();
    }

    protected function enableChildCategories(int $parentId): void
    {
        $categoryRepo = new CategoryRepo();

        $categories = $categoryRepo->findAll(['parent_id' => $parentId]);

        if ($categories->count() == 0) return;

        foreach ($categories as $category) {
            $category->published = 1;
            $category->update();
        }
    }

    protected function disableChildCategories(int $parentId): void
    {
        $categoryRepo = new CategoryRepo();

        $categories = $categoryRepo->findAll(['parent_id' => $parentId]);

        if ($categories->count() == 0) return;

        foreach ($categories as $category) {
            $category->published = 0;
            $category->update();
        }
    }

}
