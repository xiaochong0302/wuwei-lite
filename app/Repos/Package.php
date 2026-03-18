<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Course as CourseModel;
use App\Models\CoursePackage as CoursePackageModel;
use App\Models\Package as PackageModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Package extends Repository
{

    /**
     * @param array $where
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return PagerRepoInterface;
     */
    public function paginate(array $where = [], string $sort = 'latest', int $page = 1, int $limit = 15): PagerRepoInterface
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(PackageModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['title'])) {
            $builder->andWhere('title LIKE :title:', ['title' => "%{$where['title']}%"]);
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
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
     * @return PackageModel|Model|bool
     */
    public function findById(int $id)
    {
        return PackageModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param int $packageId
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findCourses(int $packageId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('c.*')
            ->addFrom(CourseModel::class, 'c')
            ->join(CoursePackageModel::class, 'c.id = cp.course_id', 'cp')
            ->where('cp.package_id = :package_id:', ['package_id' => $packageId])
            ->andWhere('c.published = 1')
            ->andWhere('c.deleted = 0')
            ->getQuery()
            ->execute();
    }

    public function countPackages(): int
    {
        return (int)PackageModel::count([
            'conditions' => 'published = 1 AND deleted = 0',
        ]);
    }

    public function countCourses(int $packageId): int
    {
        return (int)CoursePackageModel::count([
            'conditions' => 'package_id = :package_id:',
            'bind' => ['package_id' => $packageId],
        ]);
    }

}
