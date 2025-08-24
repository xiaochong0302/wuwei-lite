<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CourseUser as CourseUserModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\Logic\Course\CourseUserTrait;
use App\Validators\CourseUser as CourseUserValidator;
use Phalcon\Paginator\RepositoryInterface;

class CourseUser extends Service
{

    use CourseUserTrait;
    use AccountSearchTrait;

    public function getSourceTypes(): array
    {
        return CourseUserModel::sourceTypes();
    }

    public function getUsers(int $courseId): RepositoryInterface
    {
        $validator = new CourseUserValidator();

        $course = $validator->checkCourse($courseId);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params = $this->handleAccountSearchParams($params);

        $params['course_id'] = $course->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $repo = new CourseUserRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

        return $this->handleUsers($pager);
    }

    protected function handleUsers(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new CourseUserListBuilder();

            $items = $pager->getItems()->toArray();
            $pipeA = $builder->handleUsers($items);
            $pipeB = $builder->objects($pipeA);

            $pager->setItems($pipeB);
        }

        return $pager;
    }

}
