<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Builders\LearningList as LearningListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Course as CourseModel;
use App\Repos\Learning as LearningRepo;
use App\Validators\Course as CourseValidator;
use Phalcon\Paginator\RepositoryInterface;

class CourseLearning extends Service
{

    public function getLearnings(int $courseId): RepositoryInterface
    {
        $course = $this->findCourseOrFail($courseId);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['course_id'] = $course->id;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $learningRepo = new LearningRepo();

        $pager = $learningRepo->paginate($params, $sort, $page, $limit);

        return $this->handleLearnings($pager);
    }

    protected function findCourseOrFail(int $courseId): CourseModel
    {
        $validator = new CourseValidator();

        return $validator->checkCourse($courseId);
    }

    protected function handleLearnings(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new LearningListBuilder();

            $items = $pager->getItems()->toArray();

            $pipeA = $builder->handleCourses($items);
            $pipeB = $builder->handleChapters($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->setItems($pipeD);
        }

        return $pager;
    }

}
