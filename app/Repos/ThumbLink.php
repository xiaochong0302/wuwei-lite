<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\ThumbLink as ThumbLinkModel;
use Phalcon\Mvc\Model\Row;

class ThumbLink extends Repository
{

    /**
     * @param int $crc32
     * @return ThumbLinkModel|Row|null
     */
    public function findBySourceCRC32(int $crc32)
    {
        return ThumbLinkModel::findFirst([
            'conditions' => 'source_crc32 = :source_crc32:',
            'bind' => ['source_crc32' => $crc32],
        ]);
    }

}
