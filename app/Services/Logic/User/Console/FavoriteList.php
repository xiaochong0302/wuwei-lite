<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\User\Console;

use App\Builders\FavoriteList as FavoriteListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Services\Logic\Service as LogicService;
use Phalcon\Paginator\RepositoryInterface;

class FavoriteList extends LogicService
{

    public function handle(): RepositoryInterface
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $favoriteRepo = new CourseFavoriteRepo();

        $pager = $favoriteRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    protected function handleCourses(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $builder = new FavoriteListBuilder();

        $relations = $pager->getItems()->toArray();

        $courses = $builder->getCourses($relations);

        $items = [];

        foreach ($relations as $relation) {
            $course = $courses[$relation['course_id']] ?? null;
            $items[] = $course;
        }

        $pager->setItems($items);

        return $pager;
    }

}
