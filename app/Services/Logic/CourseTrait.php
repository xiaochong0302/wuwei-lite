<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic;

use App\Models\Course as CourseModel;
use App\Validators\Course as CourseValidator;

trait CourseTrait
{

    protected function checkCourse(int $id): CourseModel
    {
        $validator = new CourseValidator();

        return $validator->checkCourse($id);
    }

    protected function checkCourseCache(int $id): CourseModel
    {
        $validator = new CourseValidator();

        return $validator->checkCourseCache($id);
    }

}
