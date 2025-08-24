<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\ThumbLink as ThumbLinkModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;

class ThumbLink extends Repository
{

    /**
     * @param int $id
     * @return ThumbLinkModel|Row|null
     */
    public function findById($id)
    {
        return ThumbLinkModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param int $crc32
     * @return ThumbLinkModel|Row|null
     */
    public function findBySourceCRC32($crc32)
    {
        return ThumbLinkModel::findFirst([
            'conditions' => 'source_crc32 = :source_crc32:',
            'bind' => ['source_crc32' => $crc32],
        ]);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|ThumbLinkModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ThumbLinkModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
