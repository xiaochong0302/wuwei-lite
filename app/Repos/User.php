<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\User as UserModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;
use Phalcon\Paginator\RepositoryInterface;

class User extends Repository
{

    /**
     * @param array $where
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return RepositoryInterface
     */
    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(UserModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['name'])) {
            $builder->andWhere('name LIKE :name:', ['name' => "%{$where['name']}%"]);
        }

        if (!empty($where['edu_role'])) {
            if (is_array($where['edu_role'])) {
                $builder->inWhere('edu_role', $where['edu_role']);
            } else {
                $builder->andWhere('edu_role = :edu_role:', ['edu_role' => $where['edu_role']]);
            }
        }

        if (!empty($where['admin_role'])) {
            if (is_array($where['admin_role'])) {
                $builder->inWhere('admin_role', $where['admin_role']);
            } else {
                $builder->andWhere('admin_role = :admin_role:', ['admin_role' => $where['admin_role']]);
            }
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (!empty($where['active_time'][0]) && !empty($where['active_time'][1])) {
            $startTime = strtotime($where['active_time'][0]);
            $endTime = strtotime($where['active_time'][1]);
            $builder->betweenWhere('active_time', $startTime, $endTime);
        }

        if (isset($where['vip'])) {
            $builder->andWhere('vip = :vip:', ['vip' => $where['vip']]);
        }

        if (isset($where['locked'])) {
            $builder->andWhere('locked = :locked:', ['locked' => $where['locked']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        $orderBy = match ($sort) {
            'oldest' => 'id ASC',
            default => 'id DESC',
        };

        $builder->orderBy($orderBy);

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

    /**
     * @param int $id
     * @return UserModel|Row|null
     */
    public function findById($id)
    {
        return UserModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $name
     * @return UserModel|Row|null
     */
    public function findByName($name)
    {
        return UserModel::findFirst([
            'conditions' => 'name = :name:',
            'bind' => ['name' => $name],
        ]);
    }

    /**
     * @param int $id
     * @return UserModel|Row|null
     */
    public function findShallowUserById($id)
    {
        return UserModel::findFirst([
            'conditions' => 'id = :id:',
            'columns' => ['id', 'name', 'avatar', 'vip', 'title', 'about'],
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return UserModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param array $ids
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findShallowUserByIds($ids)
    {
        return UserModel::query()
            ->columns(['id', 'name', 'avatar', 'vip', 'title', 'about'])
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findLatestUsers($limit = 10)
    {
        return UserModel::query()
            ->where('deleted = 0')
            ->orderBy('id DESC')
            ->limit($limit)
            ->execute();
    }

    /**
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findTeachers()
    {
        $eduRole = UserModel::EDU_ROLE_TEACHER;

        return UserModel::query()
            ->where('edu_role = :edu_role:', ['edu_role' => $eduRole])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @return int
     */
    public function countUsers()
    {
        return (int)UserModel::count([
            'conditions' => 'deleted = 0',
        ]);
    }

    /**
     * @return int
     */
    public function countVipUsers()
    {
        return (int)UserModel::count([
            'conditions' => 'vip = 1 AND deleted = 0',
        ]);
    }

}
