<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Course;

use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class CourseInfo extends LogicService
{

    use CourseTrait;
    use CourseUserTrait;

    public function handle(int $id): array
    {
        $course = $this->checkCourse($id);

        $user = $this->getCurrentUser(true);

        $this->setCourseUser($course, $user);

        return $this->handleCourse($course, $user);
    }

    protected function handleCourse(CourseModel $course, UserModel $user): array
    {
        $service = new BasicInfo();

        $result = $service->handleBasicInfo($course);

        $result['me'] = $this->handleMeInfo($course, $user);

        return $result;
    }

    protected function handleMeInfo(CourseModel $course, UserModel $user): array
    {
        $me = [
            'allow_study' => 0,
            'allow_order' => 0,
            'progress' => 0,
            'logged' => 0,
            'joined' => 0,
            'owned' => 0,
            'reviewed' => 0,
            'favorited' => 0,
        ];

        $caseOwned = $this->ownedCourse == false;
        $casePrice = $course->regular_price > 0;

        if ($user->id > 0) {

            $me['logged'] = 1;

            if ($caseOwned && $casePrice) {
                $me['allow_order'] = 1;
            }

            if ($this->ownedCourse) {
                $me['owned'] = 1;
                $me['allow_study'] = 1;
            }

            if ($this->joinedCourse) {
                $me['joined'] = 1;
            }

            $favoriteRepo = new CourseFavoriteRepo();

            $favorite = $favoriteRepo->findCourseFavorite($course->id, $user->id);

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = 1;
            }

            if ($this->courseUser) {
                $me['reviewed'] = $this->courseUser->reviewed ? 1 : 0;
                $me['progress'] = $this->courseUser->progress;
            }
        }

        return $me;
    }

}
