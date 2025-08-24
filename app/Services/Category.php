<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services;

use App\Caches\Category as CategoryCache;
use App\Caches\CategoryList as CategoryListCache;
use App\Caches\CategoryTreeList as CategoryTreeListCache;
use App\Models\Category as CategoryModel;

class Category extends Service
{

    /**
     * 获取下拉选项
     *
     * @return array
     */
    public function getCategoryOptions(): array
    {
        $cache = new CategoryTreeListCache();

        $categories = $cache->get();

        $result = [];

        if (!$categories) return $result;

        foreach ($categories as $category) {
            $result[] = [
                'id' => $category['id'],
                'name' => $category['name'],
            ];
            if (count($category['children']) > 0) {
                foreach ($category['children'] as $child) {
                    $result[] = [
                        'id' => $child['id'],
                        'name' => sprintf('|--- %s', $child['name']),
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * 获取节点路径
     *
     * @param int $id
     * @return array
     */
    public function getCategoryPaths(int $id): array
    {
        $categoryCache = new CategoryCache();

        $category = $categoryCache->get($id);

        if (!$category) {
            return [];
        }

        if ($category->level == 1) {
            return [
                [
                    'id' => $category->id,
                    'name' => $category->name,
                ]
            ];
        }

        $parent = $categoryCache->get($category->parent_id);

        return [
            [
                'id' => $parent->id,
                'name' => $parent->name,
            ],
            [
                'id' => $category->id,
                'name' => $category->name,
            ]
        ];
    }

    /**
     * 获取子节点
     *
     * @param int $id
     * @return array
     */
    public function getChildCategories(int $id): array
    {
        $categoryListCache = new CategoryListCache();

        $categories = $categoryListCache->get();

        $result = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == $id) {
                $result[] = $category;
            }
        }

        return $result;
    }

    /**
     * 获取子节点ID
     *
     * @param int $id
     * @return array
     */
    public function getChildCategoryIds(int $id): array
    {
        $categoryCache = new CategoryCache();

        /**
         * @var CategoryModel $category
         */
        $category = $categoryCache->get($id);

        if (!$category) {
            return [];
        }

        if ($category->level == 2) {
            return [$id];
        }

        $categoryListCache = new CategoryListCache();

        $categories = $categoryListCache->get();

        $result = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == $id) {
                $result[] = $category['id'];
            }
        }

        return $result;
    }

}
