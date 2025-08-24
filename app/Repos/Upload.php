<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\Upload as UploadModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;

class Upload extends Repository
{

    /**
     * @param int $id
     * @return UploadModel|Row|null
     */
    public function findById($id)
    {
        return UploadModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $md5
     * @return UploadModel|Row|null
     */
    public function findByMd5($md5)
    {
        return UploadModel::findFirst([
            'conditions' => 'md5 = :md5: AND deleted = 0',
            'bind' => ['md5' => $md5],
        ]);
    }

    /**
     * @param string $path
     * @return UploadModel|Row|null
     */
    public function findByPath($path)
    {
        return UploadModel::findFirst([
            'conditions' => 'path = :path: AND deleted = 0',
            'bind' => ['path' => $path],
        ]);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|UploadModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return UploadModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
