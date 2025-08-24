<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\User as UserModel;
use App\Repos\ChapterUser as ChapterUserRepo;

trait ChapterUserTrait
{

    /**
     * @var bool
     */
    protected bool $ownedChapter = false;

    /**
     * @var bool
     */
    protected bool $joinedChapter = false;

    /**
     * @var ChapterUserModel|null
     */
    protected ?ChapterUserModel $chapterUser = null;

    protected function setChapterUser(ChapterModel $chapter, UserModel $user): void
    {
        if ($user->id == 0) return;

        $chapterUser = null;

        $courseUser = $this->courseUser;

        if ($courseUser) {
            $chapterUserRepo = new ChapterUserRepo();
            $chapterUser = $chapterUserRepo->findChapterUser($chapter->id, $user->id);
        }

        $this->chapterUser = $chapterUser;

        if ($chapterUser) {
            $this->joinedChapter = true;
        }

        if ($this->ownedCourse || $chapter->free == 1) {
            $this->ownedChapter = true;
        }
    }

}
