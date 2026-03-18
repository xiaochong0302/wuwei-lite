<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\ChapterUser as ChapterUserModel;
use Phalcon\Mvc\Model\Row;

class ChapterUser extends Repository
{

    /**
     * @param int $chapterId
     * @param int $userId
     * @return ChapterUserModel|Row|null
     */
    public function findChapterUser(int $chapterId, int $userId)
    {
        return ChapterUserModel::findFirst([
            'conditions' => 'chapter_id = ?1 AND user_id = ?2 AND deleted = 0',
            'bind' => [1 => $chapterId, 2 => $userId],
            'order' => 'id DESC',
        ]);
    }

}
