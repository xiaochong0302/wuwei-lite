<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Course;

use App\Models\Course as CourseModel;
use App\Models\CourseFavorite as CourseFavoriteModel;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class CourseFavorite extends LogicService
{

    use CourseTrait;

    public function handle(int $id): array
    {
        $course = $this->checkCourseCache($id);

        $user = $this->getLoginUser(true);

        $favoriteRepo = new CourseFavoriteRepo();

        $favorite = $favoriteRepo->findCourseFavorite($course->id, $user->id);

        if (!$favorite) {

            $favorite = new CourseFavoriteModel();

            $favorite->course_id = $course->id;
            $favorite->user_id = $user->id;

            $favorite->create();

        } else {

            $favorite->deleted = $favorite->deleted == 1 ? 0 : 1;

            $favorite->update();
        }

        if ($favorite->deleted == 0) {

            $action = 'do';

            $this->incrCourseFavoriteCount($course);

        } else {

            $action = 'undo';

            $this->decrCourseFavoriteCount($course);
        }

        return [
            'action' => $action,
            'count' => $course->favorite_count,
        ];
    }

    protected function incrCourseFavoriteCount(CourseModel $course): void
    {
        $course->favorite_count += 1;

        $course->update();
    }

    protected function decrCourseFavoriteCount(CourseModel $course): void
    {
        if ($course->favorite_count > 0) {
            $course->favorite_count -= 1;
            $course->update();
        }
    }

}
