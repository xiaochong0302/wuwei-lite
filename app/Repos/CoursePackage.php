<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\CoursePackage as CoursePackageModel;
use Phalcon\Mvc\Model;

class CoursePackage extends Repository
{

    /**
     * @param int $courseId
     * @param int $packageId
     * @return CoursePackageModel|Model|bool
     */
    public function findCoursePackage(int $courseId, int $packageId)
    {
        return CoursePackageModel::findFirst([
            'conditions' => 'course_id = :course_id: AND package_id = :package_id:',
            'bind' => ['course_id' => $courseId, 'package_id' => $packageId],
        ]);
    }

}
