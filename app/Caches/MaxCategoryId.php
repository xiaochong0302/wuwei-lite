<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Category as CategoryModel;

class MaxCategoryId extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 360 * 86400;

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getKey($id = null): string
    {
        return 'max-category-id';
    }

    public function getContent($id = null): int
    {
        $category = CategoryModel::findFirst(['order' => 'id DESC']);

        return $category->id ?? 0;
    }

}
