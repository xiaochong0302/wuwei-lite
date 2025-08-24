<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\Category as CategoryModel;
use Phalcon\Mvc\Model\Resultset;

class CategoryList extends Cache
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
        return 'category-list';
    }

    public function getContent($id = null): array
    {
        /**
         * @var Resultset $categories
         */
        $categories = CategoryModel::query()
            ->columns(['id', 'parent_id', 'name', 'slug', 'icon', 'priority', 'level', 'path'])
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('level ASC, priority ASC')
            ->execute();

        if ($categories->count() == 0) {
            return [];
        }

        return $categories->toArray();
    }

}
