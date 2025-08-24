<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\Category as CategoryModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;

class Category extends Repository
{

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    public function findAll($where = [])
    {
        $query = CategoryModel::query();

        $query->where('1 = 1');

        if (!empty($where['id'])) {
            $query->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['name'])) {
            $query->andWhere('name LIKE :name:', ['name' => "%{$where['name']}%"]);
        }

        if (!empty($where['level'])) {
            if (is_array($where['level'])) {
                $query->inWhere('level', $where['level']);
            } else {
                $query->andWhere('level = :level:', ['level' => $where['level']]);
            }
        }

        if (isset($where['parent_id'])) {
            $query->andWhere('parent_id = :parent_id:', ['parent_id' => $where['parent_id']]);
        }

        if (isset($where['published'])) {
            $query->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        $query->orderBy('priority ASC');

        return $query->execute();
    }

    /**
     * @param int $id
     * @return CategoryModel|Row|null
     */
    public function findById($id)
    {
        return CategoryModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return CategoryModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    public function findTopCategories()
    {
        return CategoryModel::query()
            ->where('parent_id = 0')
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('priority ASC')
            ->execute();
    }

    /**
     * @param int $categoryId
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    public function findChildCategories($categoryId)
    {
        return CategoryModel::query()
            ->where('parent_id = :parent_id:', ['parent_id' => $categoryId])
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('priority ASC')
            ->execute();
    }

    /**
     * @param $categoryId
     * @return int
     */
    public function countChildCategories($categoryId)
    {
        return (int)CategoryModel::count([
            'conditions' => 'parent_id = :parent_id: AND published = 1 AND deleted = 0',
            'bind' => ['parent_id' => $categoryId],
        ]);
    }

}
