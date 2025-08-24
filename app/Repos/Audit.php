<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Audit as AuditModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;
use Phalcon\Paginator\RepositoryInterface;

class Audit extends Repository
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

        $builder->from(AuditModel::class);

        $builder->where('1 = 1');

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['user_ip'])) {
            $builder->andWhere('user_ip = :user_ip:', ['user_ip' => $where['user_ip']]);
        }

        if (!empty($where['user_name'])) {
            $builder->andWhere('user_name = :user_name:', ['user_name' => $where['user_name']]);
        }

        if (!empty($where['req_route'])) {
            $builder->andWhere('req_route = :req_route:', ['req_route' => $where['req_route']]);
        }

        if (!empty($where['req_path'])) {
            $builder->andWhere('req_path = :req_path:', ['req_path' => $where['req_path']]);
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
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
     * @return AuditModel|Row|null
     */
    public function findById($id)
    {
        return AuditModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|AuditModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return AuditModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
