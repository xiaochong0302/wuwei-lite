<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\Media as MediaModel;
use Phalcon\Mvc\Model\Row;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Media extends Repository
{

    /**
     * @param int $id
     * @return MediaModel|Row|null
     */
    public function findById($id)
    {
        return MediaModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param int $uploadId
     * @return MediaModel|Row|null
     */
    public function findByUploadId(int $uploadId)
    {
        return MediaModel::findFirst([
            'conditions' => 'upload_id = :upload_id:',
            'bind' => ['upload_id' => $uploadId],
        ]);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|MediaModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return MediaModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
