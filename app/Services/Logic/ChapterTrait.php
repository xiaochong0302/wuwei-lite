<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterArticle as ChapterArticleModel;
use App\Models\ChapterVideo as ChapterVideoModel;
use App\Validators\Chapter as ChapterValidator;

trait ChapterTrait
{

    protected function checkChapterVideo(int $id): ChapterVideoModel
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterVideo($id);
    }

    protected function checkChapterArticle(int $id): ChapterArticleModel
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterArticle($id);
    }

    protected function checkChapter(int $id): ChapterModel
    {
        $validator = new ChapterValidator();

        return $validator->checkChapter($id);
    }

    protected function checkChapterCache(int $id): ChapterModel
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterCache($id);
    }

}
