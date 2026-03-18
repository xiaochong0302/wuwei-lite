<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\Media as MediaModel;
use Phalcon\Mvc\Model\Row;

class Media extends Repository
{

    /**
     * @param int $id
     * @return MediaModel|Row|null
     */
    public function findById(int $id)
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

}
