<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Deliver;

use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\User as UserModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\Logic\Course\CourseUserTrait;
use App\Services\Logic\Service as LogicService;

class CourseDeliver extends LogicService
{

    use CourseUserTrait;

    public function handle(CourseModel $course, UserModel $user): void
    {
        $this->revokeCourseUser($course, $user);
        $this->handleCourseUser($course, $user);
    }

    protected function handleCourseUser(CourseModel $course, UserModel $user)
    {
        $expiryTime = strtotime("+{$course->study_expiry} months");

        $sourceType = CourseUserModel::JOIN_TYPE_PURCHASE;

        $this->createCourseUser($course, $user, $expiryTime, $sourceType);
        $this->recountCourseUsers($course);
    }

    protected function revokeCourseUser(CourseModel $course, UserModel $user)
    {
        $courseUserRepo = new CourseUserRepo();

        $relations = $courseUserRepo->findByCourseAndUserId($course->id, $user->id);

        if ($relations->count() == 0) return;

        foreach ($relations as $relation) {
            if ($relation->deleted == 0) {
                $this->deleteCourseUser($relation);
            }
        }
    }

}
