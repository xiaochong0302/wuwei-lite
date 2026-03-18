<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\Resource as ResourceModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;

class Resource extends Repository
{

    /**
     * @param int $id
     * @return ResourceModel|Row|null
     */
    public function findById(int $id)
    {
        return ResourceModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|ResourceModel[]
     */
    public function findByCourseId(int $courseId)
    {
        return ResourceModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->execute();
    }

}
