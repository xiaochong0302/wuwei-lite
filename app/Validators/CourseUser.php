<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;

class CourseUser extends Validator
{

    public function checkCourseUser(int $courseId, int $userId): CourseUserModel
    {
        $repo = new CourseUserRepo();

        $courseUser = $repo->findCourseUser($courseId, $userId);

        if (!$courseUser) {
            throw new BadRequestException('course_user.not_found');
        }

        return $courseUser;
    }

    public function checkCourse(int $id): CourseModel
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkAccount(string $id): UserModel
    {
        $value = $this->filter->sanitize($id, ['trim', 'string']);

        $userValidator = new User();
        $accountValidator = new Account();

        if (CommonValidator::email($value)) {
            $account = $accountValidator->checkAccount($value);
            return $userValidator->checkUser($account->id);
        } else {
            return $userValidator->checkUser($value);
        }
    }

    public function checkExpiryTime(string $expiryTime): int
    {
        $value = $this->filter->sanitize($expiryTime, ['trim', 'string']);

        if (!CommonValidator::date($value, 'Y-m-d H:i:s')) {
            throw new BadRequestException('course_user.invalid_expiry_time');
        }

        return strtotime($value);
    }

    public function checkIfReviewed(int $courseId, int $userId): void
    {
        $repo = new CourseUserRepo();

        $courseUser = $repo->findCourseUser($courseId, $userId);

        if ($courseUser && $courseUser->reviewed) {
            throw new BadRequestException('course_user.has_reviewed');
        }
    }

    public function checkIfReviewEnabled(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        if ($course->review_enabled == 0) {
            throw new BadRequestException('course_user.review_not_enabled');
        }
    }

}
