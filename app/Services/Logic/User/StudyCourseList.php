<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\User;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;
use Phalcon\Paginator\RepositoryInterface;

class StudyCourseList extends LogicService
{

    use UserTrait;

    public function handle(int $id): RepositoryInterface
    {
        $user = $this->checkUserCache($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $repo = new CourseUserRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

        return $this->handlePager($pager);
    }

    protected function handlePager(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $builder = new CourseUserListBuilder();

        $relations = $pager->getItems()->toArray();

        $courses = $builder->getCourses($relations);

        $items = [];

        foreach ($relations as $relation) {

            $course = $courses[$relation['course_id']] ?? null;

            $items[] = [
                'id' => $relation['id'],
                'progress' => $relation['progress'],
                'duration' => $relation['duration'],
                'reviewed' => $relation['reviewed'],
                'source_type' => $relation['source_type'],
                'expiry_time' => $relation['expiry_time'],
                'create_time' => $relation['create_time'],
                'course' => $course,
            ];
        }

        $pager->setItems($items);

        return $pager;
    }

}
