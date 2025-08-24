<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\ChapterLike as ChapterLikeModel;
use Phalcon\Mvc\Model\Row;

class ChapterLike extends Repository
{

    /**
     * @param int $chapterId
     * @param int $userId
     * @return ChapterLikeModel|Row|null
     */
    public function findChapterLike($chapterId, $userId)
    {
        return ChapterLikeModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id: AND user_id = :user_id:',
            'bind' => ['chapter_id' => $chapterId, 'user_id' => $userId],
        ]);
    }

}
