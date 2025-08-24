<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\Role as RoleModel;
use App\Models\User as UserModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;

class Role extends Repository
{

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|RoleModel[]
     */
    public function findAll($where = [])
    {
        $query = RoleModel::query();

        $query->where('1 = 1');

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        return $query->execute();
    }

    /**
     * @param int $id
     * @return RoleModel|Row|null
     */
    public function findById($id)
    {
        return RoleModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|RoleModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return RoleModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    public function countUsers($roleId)
    {
        return UserModel::count([
            'conditions' => 'admin_role = :role_id:',
            'bind' => ['role_id' => $roleId],
        ]);
    }

}
