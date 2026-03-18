<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\CourseRelated as CourseRelatedModel;
use Phalcon\Mvc\Model;

class CourseRelated extends Repository
{

    /**
     * @param int $courseId
     * @param int $relatedId
     * @return CourseRelatedModel|Model|bool
     */
    public function findCourseRelated(int $courseId, int $relatedId)
    {
        return CourseRelatedModel::findFirst([
            'conditions' => 'course_id = :course_id: AND related_id = :related_id:',
            'bind' => ['course_id' => $courseId, 'related_id' => $relatedId],
        ]);
    }

}
