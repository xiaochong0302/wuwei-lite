<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Course;

use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\User as UserModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\CourseStat as CourseStatService;

trait CourseUserTrait
{

    /**
     * @var bool
     */
    protected bool $ownedCourse = false;

    /**
     * @var bool
     */
    protected bool $joinedCourse = false;

    /**
     * @var CourseUserModel|null
     */
    protected ?CourseUserModel $courseUser;

    protected function setCourseUser(CourseModel $course, UserModel $user): void
    {
        if ($user->id == 0) return;

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($course->id, $user->id);

        $this->courseUser = $courseUser;

        if ($courseUser) {
            $this->joinedCourse = true;
        }

        if ($course->teacher_id == $user->id) {

            $this->ownedCourse = true;

        } elseif ($course->regular_price == 0) {

            $this->ownedCourse = true;

        } elseif ($course->vip_price == 0 && $user->vip == 1) {

            $this->ownedCourse = true;

        } elseif ($courseUser) {

            $joinTypes = [
                CourseUserModel::JOIN_TYPE_PURCHASE,
                CourseUserModel::JOIN_TYPE_MANUAL,
            ];

            $case1 = $courseUser->deleted == 0;
            $case2 = $courseUser->expiry_time > time();
            $case3 = in_array($courseUser->join_type, $joinTypes);

            /**
             * 之前参与过课程，但不再满足条件，视为未参与
             */
            if ($case1 && $case2 && $case3) {
                $this->ownedCourse = true;
            } else {
                $this->joinedCourse = false;
            }
        }
    }

    protected function createCourseUser(CourseModel $course, UserModel $user, int $expiryTime, int $joinType): CourseUserModel
    {
        $courseUser = new CourseUserModel();

        $courseUser->course_id = $course->id;
        $courseUser->user_id = $user->id;
        $courseUser->expiry_time = $expiryTime;
        $courseUser->join_type = $joinType;

        $courseUser->create();

        return $courseUser;
    }

    protected function deleteCourseUser(CourseUserModel $relation): void
    {
        $relation->deleted = 1;

        $relation->update();
    }

    protected function recountCourseUsers(CourseModel $course): void
    {
        $statService = new CourseStatService();

        $statService->updateUserCount($course->id);
    }

    protected function allowFreeAccess(CourseModel $course, UserModel $user): bool
    {
        $result = false;

        if ($course->regular_price == 0) {
            $result = true;
        } elseif ($course->vip_price == 0 && $user->vip == 1) {
            $result = true;
        } elseif ($course->teacher_id == $user->id) {
            $result = true;
        }

        return $result;
    }

    protected function getFreeJoinType(CourseModel $course, UserModel $user): int
    {
        if ($course->teacher_id == $user->id) {
            return CourseUserModel::JOIN_TYPE_TEACHER;
        }

        return CourseUserModel::JOIN_TYPE_FREE;
    }

}
