<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Builders;

use App\Models\Category as CategoryModel;
use App\Repos\Category as CategoryRepo;

class CategoryTreeList extends Builder
{

    public function handle(): array
    {
        $categoryRepo = new CategoryRepo();

        $topCategories = $categoryRepo->findTopCategories();

        if ($topCategories->count() == 0) {
            return [];
        }

        $list = [];

        foreach ($topCategories as $category) {
            $list[] = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'icon' => $category->icon,
                'children' => $this->handleChildren($category),
            ];
        }

        return $list;
    }

    protected function handleChildren(CategoryModel $category): array
    {
        $categoryRepo = new CategoryRepo();

        $subCategories = $categoryRepo->findChildCategories($category->id);

        if ($subCategories->count() == 0) {
            return [];
        }

        $list = [];

        foreach ($subCategories as $category) {
            $list[] = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'icon' => $category->icon,
            ];
        }

        return $list;
    }

}
