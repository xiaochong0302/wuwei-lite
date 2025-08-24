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
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;

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

    protected function assignUserCourse(CourseModel $course, UserModel $user, int $expiryTime, int $joinType): void
    {
        if ($this->allowFreeAccess($course, $user)) return;

        $courseUserRepo = new CourseUserRepo();

        $relation = $courseUserRepo->findCourseUser($course->id, $user->id);

        if (!$relation) {

            $this->createCourseUser($course, $user, $expiryTime, $joinType);

        } else {

            switch ($relation->join_type) {
                case CourseUserModel::JOIN_TYPE_FREE:
                case CourseUserModel::JOIN_TYPE_TRIAL:
                case CourseUserModel::JOIN_TYPE_VIP:
                case CourseUserModel::JOIN_TYPE_TEACHER:
                    $this->createCourseUser($course, $user, $expiryTime, $joinType);
                    $this->deleteCourseUser($relation);
                    break;
                case CourseUserModel::JOIN_TYPE_MANUAL:
                    $relation->expiry_time = $expiryTime;
                    $relation->update();
                    break;
                case CourseUserModel::JOIN_TYPE_PURCHASE:
                    if ($relation->expiry_time < time()) {
                        $this->createCourseUser($course, $user, $expiryTime, $joinType);
                        $this->deleteCourseUser($relation);
                    }
                    break;
            }
        }

        $this->recountCourseUsers($course);
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
        $courseRepo = new CourseRepo();

        $userCount = $courseRepo->countUsers($course->id);

        $course->user_count = $userCount;

        $course->update();
    }

    protected function allowFreeAccess(CourseModel $course, UserModel $user)
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

    protected function getFreeJoinType(CourseModel $course, UserModel $user)
    {
        if ($course->teacher_id == $user->id) {
            return CourseUserModel::JOIN_TYPE_TEACHER;
        }

        return CourseUserModel::JOIN_TYPE_FREE;
    }

}
